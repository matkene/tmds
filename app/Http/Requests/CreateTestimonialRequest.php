<?php

namespace App\Http\Requests;


use App\Rules\CheckIfTestimonialTitleExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateTestimonialRequest extends FormRequest
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
            'title' => ['required','string',new CheckIfTestimonialTitleExists()],
            'description' => ['required','string'],
            'rating' => 'required',
            'user_id' => 'required',
            'tour_id' => 'required',
            'image' => 'required|file'
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
            'image.required' => 'Image is required',
            'rating.required' => 'Rating is required',
            'user_id.required' => 'User Id is required',
            'tour_id.required' => 'Tour Id is required'
        ];
    }
}
