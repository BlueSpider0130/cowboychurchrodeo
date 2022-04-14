<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContestantStore extends FormRequest
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
            'profile_picture' => 'nullable|image',
            'first_name'      => 'required|max:255',
            'last_name'       => 'required|max:255',           
            'address_line_1'  => 'nullable|max:255',
            'address_line_2'  => 'nullable|max:255',
            'city'            => 'nullable|max:255',
            'state'           => 'nullable|max:255',
            'postcode'        => 'nullable|max:255',
            'birthdate'       => 'nullable|date', 
            'phone' => 'required | numeric | digits:10 | starts_with:1',
            'sex' => 'required'
        ];
    }
}
