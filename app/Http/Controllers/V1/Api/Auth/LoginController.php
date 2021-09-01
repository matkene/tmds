<?php

namespace App\Http\Controllers\V1\Api\Auth;

use App\Helpers\ProcessAuditLog;
use Illuminate\Http\Request;
use App\Helpers\UserMgtHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Interfaces\UserStatusInterface;
use App\Models\User;
use App\Repositories\RegisterRepository;

class LoginController extends Controller
{
    protected $registerRepository;
    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    public function login(LoginRequest $request)
    {

        $credentials = request(['email', 'password']);

        // Check if user can login
        $userInstance = $this->registerRepository->findByEmail($request->email);
        if (!$userInstance) {
            $error = true;
            $message = 'You are not yet registered on this platform';
            $data = [];
            return JsonResponser::send($error, $message, $data, 401);
        }

        if ($userInstance->is_verified == false) {
            $error = true;
            $message = 'Account not verified! Please verify your email and try again.';
            $data = [];
            return JsonResponser::send($error, $message, $data, 400);
        }

        if ($userInstance->is_active == UserStatusInterface::INACTIVE) {
            $error = true;
            $message = 'Access denied! Your account has been deactivated.';
            $data = [];
            return JsonResponser::send($error, $message, $data, 403);
        }

        if ($userInstance->can_login == false) {
            $error = true;
            $message = 'Access denied! Please contact adminsitrator.';
            $data = [];
            return JsonResponser::send($error, $message, $data, 403);
        }


        if (!$token = auth()->attempt($credentials)) {
            $error = true;
            $message = 'Incorrect email or password';
            $data = [];
            return JsonResponser::send($error, $message, $data, 400);
        }

        // Data to return
        $data = [
            'user' => auth()->user(),
            'userRole' => $this->userRole(auth()->user()),
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => auth('api')->factory()->getTTL() * 60
        ];

        $user = User::find(auth()->user()->id);

        $dataToLog = [
            'causer_id' => auth()->user()->id,
            'action_id' => $user->id,
            'action_type' => "Models\User",
            'log_name' => "User logged in successfully",
            'description' => "{$user->firstname} {$user->lastname} logged in successfully",
        ];

        ProcessAuditLog::storeAuditLog($dataToLog);

        $error = false;
        $message = 'You are logged in successfully';
        return JsonResponser::send($error, $message, $data, 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'error' => false,
            'message' => 'Successfully logged out',
            'data' => null
        ]);
    }

    /**
     * Get logged in user role
     *
     * @param  User object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function userRole($user)
    {
        $roles = config('roles.models.role')::pluck('slug');

        foreach ($roles as $value) {
            if ($user->hasRole($value)) {
                $userRole = $value;
                break;
            }
        }

        return $userRole;
    }
}
