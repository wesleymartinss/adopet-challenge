<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:4|max:256',
            'description' => 'required|max:256',
            'categorie' => 'required|max:256',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'stock_quantity' => 'required|numeric'
        ];
    }
}
