<?php

namespace App\Http\Controllers\V1\Api\Admin;

use App\Http\Controllers\Controller;
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

    public function allUsers()
    {
        try {

            $tourInstance = $this->userRepository->allUsers();

            if (!$tourInstance) {
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }

            return JsonResponser::send(false, "Tour Record found successfully.", $tourInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }
}
