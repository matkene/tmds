<?php

namespace App\Http\Controllers\V1\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViewUserRequest;
use App\Http\Responser\JsonResponser;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function allAdmin()
    {
        try {

            $adminInstance = $this->userRepository->allAdmin();

            if (!$adminInstance) {
                return JsonResponser::send(true, "Admin not found", null, 401);
            }

            return JsonResponser::send(false, "Admin found successfully.", $adminInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function allUsers()
    {
        try {

            $tourInstance = $this->userRepository->allUsers();

            if (!$tourInstance) {
                return JsonResponser::send(true, "Users not found", null, 401);
            }

            return JsonResponser::send(false, "Users found successfully.", $tourInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function addAdmin(Request $request)
    {
        try {

            $adminInstance = $this->userRepository->addAdmin($request->all());

            if ($adminInstance['error'] == false) {
                return JsonResponser::send(false, "Admin Created Successfully", $adminInstance);
            }

            return JsonResponser::send(true, $adminInstance['message'], $adminInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function viewOne(ViewUserRequest $request)
    {
        try {

            $tourInstance = $this->userRepository->findById($request->user_id);

            if (!$tourInstance) {
                return JsonResponser::send(true, "User Record not found", null, 401);
            }

            return JsonResponser::send(false, "User Record found successfully.", $tourInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function toggleActiveStatus(ViewUserRequest $request)
    {
        try {

            $tourInstance = $this->userRepository->toggleActiveStatus($request->user_id);

            if ($tourInstance) {
                return JsonResponser::send(false, "Status Changed Successfully", null, 401);
            }

            return JsonResponser::send(true, "User not found", null, 401);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }
}
