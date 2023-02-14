<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $validations = [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)->whereNull('deleted_at')],
            'primaryColor' => ['string', 'nullable'],
            'secondaryColor' => ['string', 'nullable'],
        ];

        if( $this->user()->role == 'client' ) {
            $validations['domain'] = 'required|unique:tenants,domain,'.$this->tenant_id.',id,deleted_at,NULL';
        }

        return $validations;
    }

    public function messages()
    {
        return [
            'domain.unique' => 'Este nome já esta sendo utilizado'
        ];
    }
}
