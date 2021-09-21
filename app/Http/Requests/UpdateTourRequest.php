<?php

namespace App\Http\Requests;

use App\Rules\CheckIfTourTitleExists;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTourRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['required'],
            'children_price' => 'required',
            'adult_price' => 'required',
            'infant_price' => 'required',
            'location' => 'required|string',
            'distance' => 'required|string',
            'tour_id' => 'required',

        ];
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
            'location.required' => 'Location is required',
            'price.required' => 'Price is required',
            'image.required' => 'Image is required',
        ];
    }
}
