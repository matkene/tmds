<?php

namespace App\Rules;

use App\Models\TravelGuide;
use Illuminate\Contracts\Validation\Rule;

class CheckIfTravelGuideTitleExists implements Rule
{

    public $attributeMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $title)
    {
        $CheckIfTravelGuideTitleExists = $this->CheckIfTravelGuideTitleExists($title);

        if($CheckIfTravelGuideTitleExists){
            $this->attributeMessage =  "Title with the title {$title} already exist";
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->attributeMessage;
    }

    /**
     * Check if Travel Guide no exist
     */
    private function CheckIfTravelGuideTitleExists($title)
    {
        $titleName = Travelguide::where('title', $title)->first();

        if (is_null($titleName)) {
            return false;
        }

        if (!is_null($titleName)) {
            return true;
        }
    }
}
