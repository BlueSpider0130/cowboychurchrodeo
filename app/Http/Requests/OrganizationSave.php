<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationSave extends FormRequest
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

            'name' => [
                'required', 
                'string', 
                'max:255', 
                'unique:App\Organization,name'
            ],

            'address_line_1' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'address_line_2' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'city' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'state' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'postcode' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'country_code' => [
                'nullable', 
                'string', 
                'max:3'
            ],

            'phone' => [
                'nullable', 
                'string', 
                'max:255'
            ], 

            'email' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'admin_notes' => [
                'nullable', 
                'string', 
            ],

        ];
    }
}
