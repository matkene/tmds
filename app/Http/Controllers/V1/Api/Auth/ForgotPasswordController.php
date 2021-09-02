<?php

namespace App\Http\Controllers\v1\Api\Auth;

use App\Models\User;
use App\Mail\ResetPassword;
use App\Mail\UpdatePasswordEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Hash, DB, Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];
        $validator = Validator::make($request->only("email"), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->messages(), 'data' => null], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json([
                'error' => true,
                'message' =>  $error_message,
                'data' => null
            ]);
        }

        try {
            $email = $request->email;
            $verification_code = Str::random(30); //Generate verification code
            DB::table('user_verifications')->insert(['user_id' => $user->id, 'email' => $request->email, 'token' => $verification_code]);
            $data = [
                'email' => $email,
                'verification_code' => $verification_code,
                'subject' => "Reset Password Notification",
            ];
            Mail::to($email)->send(new ResetPassword($data));
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json([
                'error' => true,
                'message' => $error_message,
                'data' => null
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'A reset email has been sent! Please check your email.',
            'data' => null
        ]);
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $rules = [
            'password' => 'required|min:8',
            "email" => "required|email",
            "token" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->messages(), 'data' => null], 400);
        }

        $token = DB::table('user_verifications')->where('token', $request->token)->first();

        if (!$token) {
            return response()->json([
                'error' => true,
                'message' => "Invalid token",
                'data' => null
            ], 400);
        }

        $password = $request->password;
        $userdata = User::where('id', $token->user_id)->first();
        $updatePassword = $userdata->update([
            'password' => Hash::make($password),
        ]);
        DB::table('user_verifications')->where('token', $request->token)->delete();
        if (!$updatePassword) {
            return response()->json([
                'error' => true,
                'message' => 'Error occured password was not updated',
                'data' => null
            ]);
        } else {
            $data = [
                'email' => $userdata->email,
                'name' => $userdata->firstname,
                'subject' => "Password Updated Successfully.",
            ];

            Mail::to($request->email)->send(new UpdatePasswordEmail($data));

            return response()->json([
                'error' => false,
                'message' => 'Password Updated! Please login with your new password',
                'data' => null
            ]);
        }
    }
}
