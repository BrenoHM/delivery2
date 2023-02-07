<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUpdateCategoryRequest extends FormRequest
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

    // public function validationData() {
    //     return array_merge(
    //         $this->all(),
    //         [
    //             'user_id' => Auth::id()
    //         ]
    //     );
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'categorie' => 'required|unique:categories,categorie,' . $this->id . ',id,user_id,' . $this->user_id . ',deleted_at,NULL'
        ];

    }

    public function messages()
    {
        return [
            'categorie.unique' => 'Essa categoria já foi cadastrada.'
        ];
    }
}
