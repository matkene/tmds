<?php

namespace App\Http\Requests;

use App\Rules\CheckIfEmailExists;
use App\Rules\CheckIfPhoneExists;
use App\Rules\CheckIfUsernameExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'username' => ['required','string',new CheckIfUsernameExists()],
            'password' => 'required|string|min:6',
            'lastname' => 'required|string',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'account_type' => 'required',
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
            'username.required' => 'Username is required',
            'email.email' => 'Please provide a valid Email address',
            'password.required' => 'Password is required',
            'phoneno.required' => 'Phone Number is required',
            'phoneno.number' => 'Please provide a valid Phone Number',
            'date_of_birth.required' => 'Date of Birth is required',
            'gender.required' => 'Gender is required',
            'lastname.required' => 'Last Name is required',
            'firstname.required' => 'First Name is required',
            'account_type.required' => 'Account Type is required'
        ];
    }
}
