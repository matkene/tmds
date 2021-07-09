<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckIfEmailExists;
use App\Rules\CheckIfPhoneExists;

class CreateAdminRequest extends FormRequest
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
        $rules = [
            'email' => ['required','string',new CheckIfEmailExists()],
            'phoneno' => ['required','string',new CheckIfPhoneExists()],
            'password' => 'required|string|min:6',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid Email address',
            'password.required' => 'Password is required',
            'phoneno.required' => 'Phone Number is required',
            'phoneno.number' => 'Please provide a valid Phone Number',
            'lastname.required' => 'Last Name is required',
            'firstname.required' => 'First Name is required',
            'social_security_number.required' => 'Please enter your social security number',
        ];
    }
}
