<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;



class CheckIfPhoneExists implements Rule
{

    public $attributeMessage;


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $phone)
    {

        $checkIfPhoneExists = $this->checkIfPhoneExists($phone);

        if($checkIfPhoneExists){
            $this->attributeMessage =  "User with phone no {$phone} already exist";
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
     * Check if phone no exist
     */
    private function checkIfPhoneExists($phone)
    {
        $userPhone = User::where('phoneno', $phone)->first();
        if(is_null($userPhone)){
            return false;
        }
        if(!is_null($userPhone)){
            return true;
        }
    }
}
