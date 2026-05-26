<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => 'sometimes|date',
            'to' => 'sometimes|date|after_or_equal:from',
            'tenant_title' => 'sometimes|string|max:191',
            'tenant_name' => 'sometimes|string|max:191',
            'tenant_name_code' => 'sometimes|string|size:10',
            'with_tenant_title' => 'sometimes|string|max:191',
            'with_tenant_name' => 'sometimes|string|max:191',
            'with_tenant_name_code' => 'sometimes|string|size:10',
            'rent_value' => 'sometimes|numeric|min:0|max:1000000',
            'tenant_nationality' => 'sometimes|string|max:191',
            'with_tenant_nationality' => 'sometimes|string|max:191',
            'insurance_value' => 'sometimes|numeric|min:0|max:1000000',
            'phonenumber' => 'nullable|string|max:50',
            'car' => 'nullable|array',
            'car.*.type' => 'nullable|string|max:191',
            'car.*.serial' => 'nullable|string|max:191',
            'car.*.passenger_name' => 'nullable|string|max:191',
            'car.*.identity' => 'nullable|string|max:191',
        ];
    }
}
