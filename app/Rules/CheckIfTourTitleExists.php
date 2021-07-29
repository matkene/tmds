<?php

namespace App\Rules;

use App\Models\Tour;
use Illuminate\Contracts\Validation\Rule;

class CheckIfTourTitleExists implements Rule
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
        $CheckIfTourTitleExists = $this->CheckIfTourTitleExists($title);

        if($CheckIfTourTitleExists){
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
     * Check if Projecy no exist
     */
    private function CheckIfTourTitleExists($title)
    {
        $titleName = Tour::where('title', $title)->first();

        if (is_null($titleName)) {
            return false;
        }

        if (!is_null($titleName)) {
            return true;
        }
    }
}
