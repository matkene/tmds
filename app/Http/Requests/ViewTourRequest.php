<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewTourRequest extends FormRequest
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
            'tour_id' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'tour_id' => 'Tour Id Is Required',
        ];
    }
}
