<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
            'no_adult_male' => 'required',
            'no_adult_female' => 'required',
            'adult_option' => 'required',
            'no_children_male' => 'required',
            'no_children_female' => 'required',
            'children_option' => 'required',
            'no_infant_male' => 'required',
            'no_infant_female' => 'required',
            'infant_option' => 'required',
            'user_id' => 'required',
            'tour_id' => 'required',
            'no_adult_sight_seeing' => 'required',
            'no_children_sight_seeing' => 'required',
            'date_of_visit' => 'required',
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
