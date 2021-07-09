<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
// use StaffStrength\ExitMgtProcessor\Traits\ExitReasonTraits;



class CheckIfEmailExists implements Rule
{
    // use ExitReasonTraits;

    public $attributeMessage;


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $email)
    {
        // $schoolId = getSchoolId();

        $checkIfEmailExists = $this->checkIfEmailExists($email);

        if($checkIfEmailExists){
            $this->attributeMessage =  "User with the email {$email} already exist";
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
     * Check if email exist
     */
    private function checkIfEmailExists($email)
    {
        $userEmail = User::where('email', $email)->first();
        if(is_null($userEmail)){
            return false;
        }
        if(!is_null($userEmail)){
            return true;
        }
    }
}
