<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|string',
            'name' => 'required|string',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'address.city.required' => 'City is required.',
            'address.city.string' => 'City must be a string.',
            'address.district.required' => 'District is required.',
            'address.district.string' => 'District must be a string.',
            'address.street.required' => 'Street is required.',
            'address.street.string' => 'Street must be a string.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'currency.required' => 'Currency is required.',
            'currency.string' => 'Currency must be a string.',
        ];
    }
}
