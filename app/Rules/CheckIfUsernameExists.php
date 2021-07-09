<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
// use StaffStrength\ExitMgtProcessor\Traits\ExitReasonTraits;



class CheckIfUsernameExists implements Rule
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
    public function passes($attribute, $username)
    {
        // $schoolId = getSchoolId();

        $checkIfUsernameExists = $this->checkIfUsernameExists($username);

        if($checkIfUsernameExists){
            $this->attributeMessage =  "User with the username {$username} already exist";
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
    private function checkIfUsernameExists($username)
    {
        $userName = User::where('username', $username)->first();
        if(is_null($userName)){
            return false;
        }
        if(!is_null($userName)){
            return true;
        }
    }
}
