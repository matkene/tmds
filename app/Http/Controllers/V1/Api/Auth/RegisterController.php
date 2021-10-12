<?php

namespace App\Http\Controllers\V1\Api\Auth;

use App\Helpers\ProcessAuditLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Responser\JsonResponser;
use App\Interfaces\RoleInterface;
use App\Mail\UserVerifyEmail;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\RegisterRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    protected $registerRepository;
    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Store user
     */
    public function createUser(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $newUserInstance = $this->registerRepository->create([
                "lastname" => $request->lastname,
                "firstname" => $request->firstname,
                "email" => $request->email,
                "phoneno" => $request->phoneno,
                //"username" => $request->username,
                //"date_of_birth" => $request->date_of_birth,
                //"gender" => $request->gender,
                "age" => $request->age,
                "address" => $request->address,
                "account_type" => $request->account_type,
                "password" => Hash::make($request->password),
                "can_login" => false,
                "is_verified" => false,
            ]);
            if (!$newUserInstance) {
                $error = true;
                $message = "Account was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }

            if (isset($request->userRole)) {
                $userRole = $request->userRole;
            } else {
                $userRole = RoleInterface::USER;
            }

            if ($userRole) {
                $newUserInstance->attachRole($userRole);
            }

            $newUserToken = bin2hex(openssl_random_pseudo_bytes(30));
            DB::table('user_verifications')->insert(['user_id' => $newUserInstance->id, 'email' => $newUserInstance->email, 'token' => $newUserToken, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $data = [
                'email' => $request->email,
                'name' => $request->lastname . ' ' . $request->firstname,
                'user' => $newUserInstance,
                'subject' => "Account Created Successfully",
                'verification_code' => $newUserToken,
            ];
            Mail::to($request->email)->send(new UserVerifyEmail($data));
            DB::commit();

            $dataToLog = [
                'causer_id' => $newUserInstance->id,
                'action_id' => $newUserInstance->id,
                'action_type' => "Models\User",
                'log_name' => "User account created successfully",
                'description' => "{$newUserInstance->firstname} {$newUserInstance->lastname} account created successfully",
            ];

            ProcessAuditLog::storeAuditLog($dataToLog);

            $error = false;
            $message = "Account created successfully. Kindly check your email for verification link";
            $data = $newUserInstance;
            return JsonResponser::send($error, $message, $data);
        } catch (\Throwable $th) {
            DB::rollback();
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }

    /**
     * Admin Registration
     */
    public function createAdmin(CreateAdminRequest $request)
    {
        try {
            DB::beginTransaction();
            $newUserInstance = $this->registerRepository->create([
                "lastname" => $request->lastname,
                "firstname" => $request->firstname,
                "email" => $request->email,
                "phoneno" => $request->phoneno,
                "gender" => $request->gender,
                "account_type" => $request->account_type,
                "password" => Hash::make($request->password),
                "can_login" => false,
            ]);
            if (!$newUserInstance) {
                $error = true;
                $message = "Account was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }

            $userRole = RoleInterface::USER;

            if ($userRole) {
                $newUserInstance->attachRole($userRole);
            }
            $newUserToken = bin2hex(openssl_random_pseudo_bytes(30));
            DB::table('user_verifications')->insert(['user_id' => $newUserInstance->id, 'email' => $newUserInstance->email, 'token' => $newUserToken, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $data = [
                'email' => $request->email,
                'name' => $request->lastname . ' ' . $request->firstname,
                'user' => $newUserInstance,
                'subject' => "Account Created Successfully",
                'verification_code' => $newUserToken,
            ];
            Mail::to($request->email)->send(new UserVerifyEmail($data));
            DB::commit();

            $error = false;
            $message = "Account created successfully. Kindly check your email for verification link";
            $data = $newUserInstance;
            return JsonResponser::send($error, $message, $data);
        } catch (\Throwable $th) {
            DB::rollback();
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }

    /**
     * Resend Email Token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendCode(Request $request)
    {
        /**
         * Validate Data
         */
        $validate = $this->validateResendCode($request);
        /**
         * if validation fails
         */
        if ($validate->fails()) {
            return JsonResponser::send(true, "Validation Failed", $validate->errors()->all());
        }

        try {
            DB::beginTransaction();
            $email = $request->email;
            $user = User::where("email", $email)->first();
            if (!$user) {
                return JsonResponser::send(true, "User not found", null, 404);
            }

            if ($user->is_verified) {
                return JsonResponser::send(true, "Account already verified", null, 400);
            }

            $verification_code = Str::random(30); //Generate verification code
            DB::table('user_verifications')->insert(['user_id' => $user->id, 'email' => $email, 'token' => $verification_code, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);

            $maildata = [
                'email' => $email,
                'name' => $user->firstname,
                'verification_code' => $verification_code,
                'subject' => "Please verify your email address.",
                // "recruiter" => $user->hasRole("recruiters")
            ];

            Mail::to($email)->send(new VerifyEmail($maildata));
            DB::commit();
            return JsonResponser::send(false, "Verification link sent successfully.", null);
        } catch (\Throwable $th) {
            DB::rollBack();
            logger($th);
            return JsonResponser::send(true, "Internal Server Error", null);
        }
    }

    /**
     * Validate resend code request
     */
    protected function validateResendCode($request)
    {
        $rules = [
            'email' => 'required|email|max:255',
        ];

        $validatedData = Validator::make($request->all(), $rules);
        return $validatedData;
    }
}
