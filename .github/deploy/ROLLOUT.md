# Rollout Plan — Ithmar (Laravel 10)

Generated using the `devops-rollout-plan` skill. Read top-to-bottom before the first
deploy. Treat this as the runbook on every subsequent rollout.

---

## 1. Executive Summary

| Item | Value |
| --- | --- |
| What | Composer-only CI/CD: build vendor on CI, rsync app code to shared host, run artisan post-deploy. |
| Why | Replace manual FTP/SSH copies; freeze JS pipeline (handled separately on the server under `/public`). |
| Trigger | Push to `master` or manual `workflow_dispatch`. |
| Affected systems | App code under `${DEPLOY_PATH}` on the production host. |
| **Untouched (by design)** | `public/` directory and `.env` on the remote — see §3. |
| Expected downtime | ~30–60s (Laravel maintenance mode while artisan runs). |
| Risk level | **Medium.** Touches code + DB migrations. Easy rollback via vendor/code re-rsync from prior tag. |

---

## 2. Prerequisites & Approvals

### Required approvals
- Tech lead sign-off on first run.
- DB owner sign-off if the push contains a migration that alters tables.

### Required GitHub Secrets (Settings → Environments → `production`)
| Secret | Purpose |
| --- | --- |
| `DEPLOY_HOST` | Production hostname or IP. |
| `DEPLOY_PORT` | SSH port (usually 22). |
| `DEPLOY_USER` | SSH user (must own files under `DEPLOY_PATH`). |
| `DEPLOY_PATH` | Absolute path to the Laravel root on the server (the dir that contains `artisan`). |
| `DEPLOY_SSH_KEY` | Private key (PEM). The matching public key sits in the server's `~/.ssh/authorized_keys`. |
| `DEPLOY_KNOWN_HOSTS` | Optional pinned host key. If empty, the workflow runs `ssh-keyscan` (less safe, fine for first run). |
| `DEPLOY_HEALTH_URL` | Public URL to GET for the smoke test (e.g., `https://example.com/`). |

### Pre-deployment backups (manual, before first deploy)
1. `cp -a ${DEPLOY_PATH} ${DEPLOY_PATH}.pre-cicd-$(date +%F)`
2. `mysqldump -u … > db-pre-cicd-$(date +%F).sql`
3. Confirm `${DEPLOY_PATH}/.env` and `${DEPLOY_PATH}/public/` are included in that copy.

---

## 3. Preflight Checks

Before merging to `master`:

- [ ] `composer validate --strict` passes locally.
- [ ] `composer.lock` committed and reflects `composer.json`.
- [ ] No `package.json` build step is required for this change. **The pipeline does not run `npm`/`vite`/`mix`.** Pre-built JS/CSS lives under `public/` on the server and is preserved.
- [ ] No edits to `public/` are expected to ship — `public/` is excluded from rsync.
- [ ] No edits to `.env` are expected to ship — `.env` is excluded from rsync.
- [ ] If the change includes a migration, it is reversible (or has a documented manual revert).
- [ ] Server has PHP 8.2 with required extensions (mbstring, xml, intl, pdo_mysql, gd, bcmath, zip, fileinfo, redis).

### Go / no-go checklist (in CI, automatically)
- [x] `build` job green (composer install + validate + artifact).
- [x] `deploy` job green (rsync + artisan + smoke test 200/302).

---

## 4. Step-by-Step Rollout Procedure

The pipeline performs every step. Listed for traceability.

### Phase A — Build (CI runner)
1. Checkout source.
2. Setup PHP 8.2 + Composer 2.
3. `composer validate --strict`.
4. `composer install --no-dev --optimize-autoloader --prefer-dist`.
5. Tar a release archive (uses `.github/deploy/exclude.txt`) and upload as artifact.

### Phase B — Deploy (CI → server, SSH)
1. Configure SSH (private key + known_hosts).
2. **Pre-deploy backup** — tar the remote `vendor/` to `vendor-backup-<ts>.tgz`.
3. **Maintenance ON** — `php artisan down --retry=15 --render='errors::503'`.
4. **Rsync** the project to `${DEPLOY_PATH}` with `--delete-after`, applying `exclude.txt`:
   - `public/` excluded (server-owned uploads/builds preserved).
   - `.env` excluded (server-owned secrets preserved).
   - `node_modules/`, `package*.json`, `webpack.mix.js`, `resources/js`, `resources/sass`, tests, VCS, IDE — excluded.
5. **Ship `.htaccess`** explicitly to root (root → `/public/` redirect for shared hosting setups where the document root cannot be changed).
6. **Artisan post-deploy** (in order):
   - `config:clear` → `route:clear` → `view:clear`
   - `migrate --force`
   - `config:cache` → `route:cache` → `view:cache`
   - `optimize`
7. **Maintenance OFF** — `php artisan up` (runs even if previous step fails, via `if: always()`).
8. **Smoke test** — `curl` the health URL up to 5×; require `200`/`302`.

### Phase C — Manual verification (operator, 5 min)
- Hit `${DEPLOY_HEALTH_URL}` from a browser.
- Log in as a test investor → load `/dashboard`.
- Open the Admin → Contracts page → confirm list renders.
- Tail `storage/logs/laravel.log` on the server for new exceptions.

---

## 5. Verification Signals

| Window | Signal | Threshold |
| --- | --- | --- |
| **0–2 min** | CI deploy job green; smoke test 200/302; `php artisan up` exit 0. | All must pass. |
| **2–5 min** | Login flow works; admin list pages render; no 500s in `laravel.log`. | 0 new 5xx. |
| **5–15 min** | DB migrations applied (`php artisan migrate:status`); contract draft + payment endpoints respond. | 0 schema errors. |
| **15+ min** | Error rate flat vs. baseline; queue workers (if any) catching up. | No sustained spike. |

---

## 6. Rollback Procedure

### Decision criteria — initiate rollback if any are true
- Smoke test fails after maintenance OFF.
- New 5xx rate > baseline + 5/min for 5+ minutes.
- A migration fails partway and leaves the DB inconsistent.
- Login/checkout endpoint regression confirmed by manual test.

### Rollback steps
1. **Code:** SSH in, restore previous code via:
   ```bash
   cd ${DEPLOY_PATH}
   php artisan down
   tar -xzf vendor-backup-<ts>.tgz   # restore vendor
   git fetch --all --tags
   git checkout <previous-tag-or-sha>  # only if repo is on the server; otherwise re-run a previous workflow run
   php artisan migrate:rollback --step=1   # ONLY if the bad release added a migration
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   php artisan up
   ```
2. **DB:** if `migrate:rollback` cannot reverse cleanly, restore from the pre-deploy `mysqldump`.
3. **`.env` / `public/`:** never touched by the pipeline, so nothing to restore.

### Post-rollback verification
- Hit health URL → 200/302.
- Log in as test user.
- Confirm `migrate:status` matches the previous release's expected state.

### Communication
- Post in the team channel: "Rollback complete to `<sha>`. Cause: <one-liner>. Investigating."

---

## 7. Communication Plan

| Time | Audience | Channel | Content |
| --- | --- | --- | --- |
| T-24h (planned releases) | Tech lead + ops | Slack/email | Window, scope, expected impact. |
| Deploy start | #deploys | Slack | "Deploying `<sha>` to prod. Maintenance ~1 min." |
| Deploy success | #deploys | Slack | "Deployed `<sha>`. Smoke test green. URL: …" |
| Deploy failure / rollback | #deploys + on-call | Slack + page | "Rollback in progress. Cause: …" |
| Post-mortem (if rollback) | Engineering | Doc | Within 48h. |

---

## 8. Post-Deployment Tasks

- **+1h** — Review `laravel.log`, web server access log, and DB slow log for anomalies.
- **+24h** — Review error tracker; confirm no sustained spike.
- **+1 week** — If multiple deploys ran cleanly, retire pre-CI/CD manual backup directory created in §2.

---

## 9. Contingency Plans

| Scenario | Symptoms | Response |
| --- | --- | --- |
| **Migration fails midway** | `migrate --force` non-zero; mixed schema. | Step 6 above; if not reversible, restore DB from `mysqldump`. |
| **`public/` accidentally deleted on remote** | Static assets 404; mix-manifest missing. | Confirm `.github/deploy/exclude.txt` contains `public/`. Restore `public/` from the §2 backup directory. |
| **`.env` overwritten** | App boots with wrong DB/keys. | Confirm `.env` is in `exclude.txt`. Restore from §2 backup. Rotate any leaked keys. |
| **SSH key compromised** | Unexpected logins on the host. | Remove key from `authorized_keys`; rotate `DEPLOY_SSH_KEY` secret; rotate any host secrets that key could read. |
| **Composer install fails on CI** | Build job red. | Re-run; if still failing, check PHP extension matrix in `deploy.yml` `extensions:` line. |

---

## 10. Contact Information

Fill in before first production run.

| Role | Name | Channel |
| --- | --- | --- |
| Primary on-call | _todo_ | _todo_ |
| Secondary on-call | _todo_ | _todo_ |
| DB owner | _todo_ | _todo_ |
| Hosting / infra | _todo_ | _todo_ |
| Escalation (eng lead) | _todo_ | _todo_ |

---

## Appendix A — Why we ignore `package.json`

Front-end assets under `public/js/`, `public/css/`, and `public/build/` are managed
out-of-band on the server (manual mix builds, vendor uploads). Running `npm`/`mix`
in CI would compete with that workflow and risk overwriting in-flight work. The
pipeline therefore:

- Excludes `package.json`, `package-lock.json`, `webpack.mix.js`, `node_modules/`,
  and source dirs `resources/js/`, `resources/sass/` from the rsync.
- Excludes `public/` entirely so the server's compiled assets and uploads survive.

If/when JS builds move into CI, add a `build-assets` job that runs `npm ci && npm run prod`,
publish `public/build/` only (not the entire `public/`), and update `exclude.txt`.

## Appendix B — `.htaccess` (root)

The shared-hosting layout uses a root `.htaccess` to forward all requests into
`public/` (where Laravel's own `public/.htaccess` takes over). The CI/CD ships
the root file explicitly via a separate rsync step so it cannot be missed by an
exclude rule. The server's `public/.htaccess` is **not** synced (whole `public/` is excluded).
