<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WalletWithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'holder_name' => 'required|string|max:191',
            'bank_name' => 'required|string|max:191',
            'bank_account' => 'nullable|string|max:191',
            'iban' => 'nullable|string|max:191',
        ];
    }
}
