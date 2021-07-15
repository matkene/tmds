<?php

namespace App\Http\Requests;

use App\Rules\CheckIfEventExists;
use Illuminate\Foundation\Http\FormRequest;

class ViewEventRequest extends FormRequest
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
            'event_id' => ['required', 'integer', new CheckIfEventExists()],
        ];
    }

    public function messages()
    {
        return [
            'event_id' => 'Event Id Is Required',
        ];
    }
}
