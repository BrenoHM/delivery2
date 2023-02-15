<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateFreightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'neighborhood' => 'required|unique:freights,neighborhood,' . $this->id . ',id,tenant_id,' . $this->tenant_id . ',city,' . $this->city . ',state,' . $this->state . ',deleted_at,NULL',
            'price' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'neighborhood.unique' => 'Este bairro já foi cadastrado.'
        ];
    }
}
