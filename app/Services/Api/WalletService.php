<?php

namespace App\Services\Api;

use App\Contracts\Services\WalletServiceInterface;
use App\Models\BankingInfo;
use App\Models\CashRequest;
use App\Models\Wallet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WalletService implements WalletServiceInterface
{
    public function getBalanceForUser(int $userId): array
    {
        $base = Wallet::where('user_id', $userId);

        $creditTotal = (clone $base)->where('request_id', '!=', '')->where('type', '!=', 'booking_downpayment')
            ->sum(DB::raw('COALESCE(amount, credit)'));

        $addedCredit = (clone $base)->addedCredit()->sum(DB::raw('COALESCE(amount, credit)'));
        $lockedCredit = (clone $base)->lockedCredit()->sum(DB::raw('COALESCE(amount, credit)'));
        $withdrawableCredit = (clone $base)->withdrawableCredit()->sum(DB::raw('COALESCE(amount, credit)'));

        return [
            'credit_total' => (float) $creditTotal,
            'added_credit' => (float) $addedCredit,
            'locked_credit' => (float) $lockedCredit,
            'withdrawable_credit' => (float) $withdrawableCredit,
        ];
    }

    public function getTransactionsForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return Wallet::where('user_id', $userId)
            ->whereRaw('COALESCE(amount, credit) != 0')
            ->with('contract')
            ->orderBy('id', 'DESC')
            ->paginate($perPage);
    }

    public function getTransaction(int $id, int $userId): ?object
    {
        return Wallet::where('id', $id)->where('user_id', $userId)->first();
    }

    public function addBalance(int $userId, float $amount, string $type = 'investor_add', ?string $modelType = null, ?int $modelId = null): object
    {
        return Wallet::create([
            'user_id' => $userId,
            'credit' => $amount,
            'amount' => $amount,
            'type' => $type,
            'model_type' => $modelType,
            'model_id' => $modelId,
        ]);
    }

    public function getWithdrawableBalance(int $userId): float
    {
        return (float) Wallet::where('user_id', $userId)->withdrawableCredit()->sum(DB::raw('COALESCE(amount, credit)'));
    }

    public function getBankInfo(int $userId): ?object
    {
        return BankingInfo::where('user_id', $userId)->first();
    }

    public function createWithdrawRequest(int $userId, array $data): object
    {
        $credit = Wallet::where('user_id', $userId)->withdrawableCredit();
        $creditSum = $credit->sum(DB::raw('COALESCE(amount, credit)'));

        $cashCreate = CashRequest::create([
            'user_id' => $userId,
            'amount' => $creditSum,
            'holder_name' => $data['holder_name'] ?? '',
            'bank_name' => $data['bank_name'] ?? '',
            'bank_account' => $data['bank_account'] ?? '',
            'iban' => $data['iban'] ?? '',
            'extra' => serialize($data['extra'] ?? []),
        ]);

        $credit->get()->each(function ($cred) use ($cashCreate) {
            $cred->update(['request_id' => $cashCreate->id]);
        });

        return $cashCreate;
    }
}
