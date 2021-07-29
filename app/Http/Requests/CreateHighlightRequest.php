<?php

namespace App\Http\Requests;


use App\Rules\CheckIfHighlightTitleExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateHighlightRequest extends FormRequest
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
            'title' => ['required','string',new CheckIfHighlightTitleExists()],
            'description' => 'required|string',
            'image' => 'required|file',
            'video' => 'required|file',
            'slug' => 'required|string'

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
            'image.required' => 'Image is is required',
            'video.required' => 'Video is required',
            'slug.required' => 'Slug is required',

        ];
    }
}
