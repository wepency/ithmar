<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'tenant_title' => 'required|string|max:191',
            'tenant_name' => 'required|string|max:191',
            'tenant_name_code' => 'required|string|size:10',
            'with_tenant_title' => 'required|string|max:191',
            'with_tenant_name' => 'required|string|max:191',
            'with_tenant_name_code' => 'required|string|size:10',
            'rent_value' => 'required|numeric|min:0|max:1000000',
            'tenant_nationality' => 'required|string|max:191',
            'with_tenant_nationality' => 'required|string|max:191',
            'insurance_value' => 'required|numeric|min:0|max:1000000',
            'phonenumber' => 'nullable|string|max:50',
            'car' => 'nullable|array',
            'car.*.type' => 'nullable|string|max:191',
            'car.*.serial' => 'nullable|string|max:191',
            'car.*.passenger_name' => 'nullable|string|max:191',
            'car.*.identity' => 'nullable|string|max:191',
        ];
    }
}
