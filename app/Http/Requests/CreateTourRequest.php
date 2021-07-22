<?php

namespace App\Http\Requests;

use App\Rules\CheckIfTitleExists;
use App\Rules\CheckIfDescriptionExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateTourRequest extends FormRequest
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
            'description' => ['required'],
            'price' => 'required|string',
            'image' => 'required|string',
            'location' => 'required|string',
            'distance' => 'required|string',
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
            'distance.required' => 'Distance is required',
            'ratings.required' => 'Ratings is required',
            'location.required' => 'Location is required',
            'price.required' => 'Price is required',
        ];
    }
}
