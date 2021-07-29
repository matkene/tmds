<?php

namespace App\Http\Requests;


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
            'no_adults' => 'required|string',
            'no_children' => 'required|string',
            'no_infants' => 'required|string',
            'date_of_visit' => 'required|string',
            'ticket_no' => 'required|string',
            'user_id' => 'required|string',
            'tour_id' => 'required|string',
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
            'no_adults.required' => 'No of Adults is required',
            'no_children.required' => 'No of Children is required',
            'no_infants.required' => 'No of Infants is required',
            'date_of_visit.required' => 'Date of Visit is required',
            'ticket_no.required' => 'Ticket No is required',
            'user_id.required' => 'User Id is required',
            'tour_id.required' => 'Tour Id is required',
        ];
    }
}
