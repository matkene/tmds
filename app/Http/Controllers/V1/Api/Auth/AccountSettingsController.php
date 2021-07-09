<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, Hash;

class AccountSettingsController extends Controller
{
    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        try {
            $credentials = $request->only('old_password', 'password');
            $rules = [
                'old_password' => 'required',
                'password' => 'required'
            ];
            $validateRequest = Validator::make($credentials, $rules);
            if ($validateRequest->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'One or more field are required',
                    'data' => null
                ]);
            }
            $user = auth()->user();

            $hashedPasword = $user->password;
            // check if old_password is same with d db password
            if (!Hash::check($request->old_password, $hashedPasword)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Current password is Incorrect',
                    'data' => null
                ]);
            }
            // check if new password is not d same with old password
            if (Hash::check($request->password, $hashedPasword)) {
                return response()->json([
                    'error' => true,
                    'message' => 'New password cannot be the same as old password',
                    'data' => null
                ]);
            }
            $user->update([
                'password' => Hash::make($request->password),
            ]);


            return response()->json([
                'error' => false,
                'message' => 'Password Updated!',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => null
            ]);
        }
    }
}
