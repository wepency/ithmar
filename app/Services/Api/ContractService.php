<?php

namespace App\Services\Api;

use App\Contracts\Services\ContractServiceInterface;
use App\Models\Contract;
use App\Models\ContractCar;
use App\Models\contractService;
use App\Enums\ContractStepType;
use App\Models\History;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\ContractNotifications;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContractService implements ContractServiceInterface
{
    public function listForUser(int $userId, ?string $type = null, ?string $search = null, int $perPage = 15): array
    {
        $query = Contract::where('user_id', $userId);

        if ($type === 'active') {
            $query->active();
        } elseif ($type === 'signed') {
            $query->signed();
        }

        if ($search !== null && $search !== '') {
            $query->where('code', 'like', '%' . $search . '%');
        }

        $contracts = $query->with('unit', 'beach', 'sector', 'cars')->orderBy('id', 'DESC')->paginate($perPage);

        $allCount = Contract::where('user_id', $userId)->count();
        $activeCount = Contract::where('user_id', $userId)->active()->count();
        $signedCount = Contract::where('user_id', $userId)->signed()->count();

        return [
            'contracts' => $contracts,
            'statics' => [
                'no_of_contracts' => $allCount,
                'active_contracts' => $activeCount,
                'signed_contracts' => $signedCount,
            ],
        ];
    }

    public function findForUser(int $contractId, int $userId): ?object
    {
        return Contract::where('user_id', $userId)
            ->with('unit', 'beach', 'sector', 'cars', 'services')
            ->find($contractId);
    }

    public function create(int $userId, array $data): object
    {
        $settings = Setting::first();
        $priceBefore = $settings->price_before_vat ?? 0;
        $priceAfter = $settings->price_after_vat ?? 0;
        $vat = vat_without_percent($priceBefore, $priceAfter);

        $unit = Unit::findOrFail($data['unit_id']);

        $contract = Contract::create([
            'unit_id' => $unit->id,
            'sector_id' => $unit->sector_id,
            'beach_id' => $unit->beach_id,
            'tenant_title' => $data['tenant_title'],
            'tenant_name' => $data['tenant_name'],
            'tenant_name_code' => $data['tenant_name_code'] ?? '',
            'tenant_nationality' => $data['tenant_nationality'] ?? '',
            'with_tenant_title' => $data['with_tenant_title'],
            'with_tenant_name' => $data['with_tenant_name'],
            'with_tenant_name_code' => $data['with_tenant_name_code'] ?? '',
            'with_tenant_nationality' => $data['with_tenant_nationality'] ?? '',
            'insurance_value' => $data['insurance_value'] ?? 0,
            'rent_value' => $data['rent_value'],
            'from' => Carbon::parse($data['from'] . ' 15:00:00')->format('Y-m-d H:i:s'),
            'to' => Carbon::parse($data['to'] . ' 15:00:00')->format('Y-m-d H:i:s'),
            'user_id' => $userId,
            'status' => 0,
            'price' => $priceBefore,
            'vat' => $vat,
            'total' => $priceAfter,
            'payment_type' => 'phone',
            'phonenumber' => $data['phonenumber'] ?? null,
            'code' => get_code(),
            'token' => time() + rand(1111, 9999),
        ]);

        $this->syncCars($contract->id, $data['cars'] ?? []);
        $this->syncContractServices($contract->id, $userId, $data);

        User::where('role', 'admin')->get()->each(function ($user) use ($contract) {
            @$user->notify(new ContractNotifications($contract));
        });

        record_contract_step($contract, ContractStepType::CREATED, [
            'code' => $contract->code,
            'unit_id' => $contract->unit_id,
            'from' => $contract->from,
            'to' => $contract->to,
        ], $userId);

        $contract->load('unit', 'beach', 'sector', 'cars');
        return $contract;
    }

    public function update(int $contractId, int $userId, array $data): object
    {
        $contract = Contract::where('user_id', $userId)->findOrFail($contractId);

        $updatable = [
            'tenant_title', 'tenant_name', 'tenant_name_code', 'tenant_nationality',
            'with_tenant_title', 'with_tenant_name', 'with_tenant_name_code', 'with_tenant_nationality',
            'insurance_value', 'rent_value', 'phonenumber',
        ];

        foreach ($updatable as $key) {
            if (array_key_exists($key, $data)) {
                $contract->$key = $data[$key];
            }
        }

        if (isset($data['from'])) {
            $contract->from = Carbon::parse($data['from'] . ' 15:00:00')->format('Y-m-d H:i:s');
        }
        if (isset($data['to'])) {
            $contract->to = Carbon::parse($data['to'] . ' 15:00:00')->format('Y-m-d H:i:s');
        }

        $contract->save();

        if (isset($data['cars'])) {
            ContractCar::where('contract_id', $contract->id)->delete();
            $this->syncCars($contract->id, $data['cars']);
        }

        record_contract_step($contract, ContractStepType::UPDATED, ['changes' => array_keys($data)], $userId);

        return $contract->fresh(['unit', 'beach', 'sector', 'cars', 'services']);
    }

    public function cancel(int $contractId, int $userId): bool
    {
        $contract = Contract::where('user_id', $userId)->findOrFail($contractId);
        $contract->update(['is_cancelled' => 1]);
        record_contract_step($contract, ContractStepType::CANCELLED, null, $userId);
        return true;
    }

    private function syncCars(int $contractId, array $cars): void
    {
        $sortOrder = 1;
        foreach ($cars as $c) {
            $type = $c['type'] ?? '';
            $serial = $c['serial'] ?? '';
            $passengerName = $c['passenger_name'] ?? '';
            $identity = $c['identity'] ?? '';
            if ($type !== '' || $serial !== '' || $passengerName !== '' || $identity !== '') {
                ContractCar::create([
                    'contract_id' => $contractId,
                    'car_type' => $type,
                    'car_serial' => $serial,
                    'passenger_name' => $passengerName,
                    'identity' => $identity,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }

    private function syncContractServices(int $contractId, int $userId, array $data): void
    {
        $user = User::find($userId);
        $services = $user && $user->services->isNotEmpty() ? $user->services->toArray() : [];
        $servicesTotal = $user && $user->services->isNotEmpty() ? $user->services()->sum('price') : 0;

        if (!empty($services)) {
            contractService::create([
                'contract_id' => $contractId,
                'service_data' => serialize($services),
            ]);
        }

        Contract::where('id', $contractId)->update(['services_total' => $servicesTotal]);
    }
}
