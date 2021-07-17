<?php

namespace App\Http\Requests;

use App\Rules\CheckIfTitleExists;
use App\Rules\CheckIfDescriptionExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            'title' => ['required','string',new CheckIfTitleExists()],
            'description' => ['required','string'],
            'start_date' => 'required',
            'end_date' => 'required',
            'tags' => 'required|string',
            'image' => 'required|string',
            'location' => 'required|string'


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
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'start_date.required' => 'Start Date is required',
            'end_date.required' => 'End Date is required',
            'location.required' => 'Location is required',
            'tags.required' => 'Tags is required',
        ];
    }
}
