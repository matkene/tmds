<?php

namespace App\Rules;

use App\Models\Event;
use Illuminate\Contracts\Validation\Rule;

class CheckIfDescriptionExists implements Rule
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
    public function passes($attribute, $description)
    {
        $CheckIfDescriptionExists = $this->CheckIfDescriptionExists($description);

        if ($CheckIfDescriptionExists) {
            $this->attributeMessage = "Description with description {$description} already exist";
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
     * Check if Event no exist
     */
    private function CheckIfDescriptionExists($description)
    {
        $descriptionName = Event::where('description', $description)->first();

        if (is_null($descriptionName)) {
            return false;
        }

        if (!is_null($descriptionName)) {
            return true;
        }
    }
}
