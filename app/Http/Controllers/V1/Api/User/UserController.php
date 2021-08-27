<?php

namespace App\Http\Controllers\V1\Api\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Responser\JsonResponser;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get user details
     */
    public function index()
    {
        try {
            $userId = auth()->user()->id;

            $userInstance = $this->userRepository->findById($userId);

            if (!$userInstance) {
                return JsonResponser::send(true, "Record not found", null, 401);
            }

            return JsonResponser::send(false, "Record found successfully.", $userInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    /**
     * Edit User
     */
    public function update(Request $request)
    {
        try {
            $userId = auth()->user()->id;

            $userInstance = $this->userRepository->findById($userId);

            if (!$userInstance) {
                return JsonResponser::send(true, "Record not found", null, 401);
            }

            $validate = $this->validateUser($request);

            if ($validate->fails()) {
                return JsonResponser::send(true, 'Validation Failed', $validate->errors()->all());
            }


            DB::beginTransaction();
            $updateUserInstance = $userInstance->update([
                "lastname" => $request->lastname,
                "firstname" => $request->firstname,
                "phoneno" => $request->phoneno,
                "dateemail_of_birth" => $request->date_of_birth,
                "email" => $request->email,
                "address" => $request->address,
                "state" => $request->state,
                "country" => $request->country
            ]);
            if (!$updateUserInstance) {
                $error = true;
                $message = "Account was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            // if ($file = $request->file('resume')) {
            //     $name = $file->getClientOriginalName();
            //     $fileName = config('app.url') . '/Resume/' . $newUserInstance->email . '/' . $request->lastname . '_' . date("Y-m-d") . '_' . time() . $name;
            //     $file->move(('Resume/' . $newUserInstance->email . '/'), $fileName);

            //     $storeResume = Document::firstOrCreate([
            //         "user_id" => $newUserInstance->id,
            //         "type" => "Resume",
            //         "file" => $fileName
            //     ]);
            // }

            DB::commit();

            $error = false;
            $message = "Account updated successfully.";
            $data = $updateUserInstance;
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
     * Validate User Request
     */
    public function validateUser(Request $request)
    {

        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'phoneno' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'highest_qualification' => 'required',
            'interest' => 'required',
            'years_of_experience' => 'required',
        ];

        $validateUser = Validator::make($request->all(), $rules);
        return $validateUser;
    }
}
