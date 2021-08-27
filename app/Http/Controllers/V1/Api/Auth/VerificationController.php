<?php

namespace App\Http\Controllers\V1\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responser\JsonResponser;
use App\Models\User;
use App\Repositories\RegisterRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    protected $registerRepository;
    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }
    /**
     * API Verify User email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $checkVerificationCode = DB::table('user_verifications')->where('token', $verification_code)->first();

        if (!is_null($checkVerificationCode)) {
            $user = $this->registerRepository->findById($checkVerificationCode->user_id);
            if (!$user) {
                $error = true;
                $message = 'User not found';
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            $userName = $user->firstname;

            if ($user->is_verified == 1) {
                $error = true;
                $message = 'Account already Verified';
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }

            $user->update(['is_verified' => true, 'can_login' => true]);

            $token = JWTAuth::fromUser($user);

            $data = [
                'accessToken' => $token,
                'tokenType' => 'Bearer',
                "user" => $user
            ];

            DB::table('user_verifications')->where('token', $verification_code)->delete();

            return JsonResponser::send(false, "Account Verification successful.", $data);
        }

        return JsonResponser::send(true, "Verification code is invalid.", null, 400);
    }
}
