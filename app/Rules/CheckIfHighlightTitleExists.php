<?php

namespace App\Rules;

use App\Models\Highlight;
use Illuminate\Contracts\Validation\Rule;

class CheckIfHighlightTitleExists implements Rule
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
        $CheckIfHighlightTitleExists = $this->CheckIfHighlightTitleExists($title);

        if($CheckIfHighlightTitleExists){
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
     * Check if Title not exist
     */
    private function CheckIfHighlightTitleExists($title)
    {
        $titleName = Highlight::where('title', $title)->first();

        if (is_null($titleName)) {
            return false;
        }

        if (!is_null($titleName)) {
            return true;
        }
    }
}
