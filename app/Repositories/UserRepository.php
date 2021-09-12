<?php

namespace App\Repositories;

use App\Mail\NewAdminUserEmail;
use App\Models\RoleUser;
use App\Models\Tour;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use jeremykenedy\LaravelRoles\Models\Role;
use phpDocumentor\Reflection\Types\Boolean;

class UserRepository
{

    private $modelInstance;

    public function __construct(User $user)
    {
        $this->modelInstance = $user;
    }

    public function findById($userId)
    {
        return $this->modelInstance::with('booking', 'testimonials')->where('id', $userId)->first();
    }

    public function allUsers()
    {
        return $this->modelInstance::with('booking', 'testimonials')->paginate(5);
    }

    public function allAdmin()
    {
        return $this->modelInstance::query()->whereHas("roles", function ($q) {
            $q->whereNotIn("slug", ["user"]);
        })->get();
    }

    public function addAdmin($request)
    {

        // Check if role Exists
        $role = Role::find($request['role_id']);
        if (is_null($role)) {
            return [
                'error' => true,
                'message' => 'Role Id not found',
                'data' => [],
            ];
        }


        // Check if email exists
        $emailExists = $this->modelInstance::where('email', $request['email'])->first();
        if ($emailExists) {
            return [
                'error' => true,
                'message' => 'Email address already been used',
                'data' => [],
            ];
        }

        // Check Tour Exist
        $tourInstance = Tour::find($request['tour_id']);
        if (is_null($tourInstance)) {
            return [
                'error' => true,
                'message' => 'Tour Id Not Found',
                'data' => [],
            ];
        }

        // Check if a user has been assigned to a tour
        if (!empty($tourInstance->user_id)) {
            return [
                'error' => true,
                'message' => 'There is a user already assigned to this tour location',
                'data' => [],
            ];
        }

        // Create Default Password
        $string = 'ABCDEFGHIKLLMNOPQRSTUBWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%*()';
        $shuffledString = str_shuffle($string);
        $password = substr($shuffledString, 0, 10);

        DB::beginTransaction();
        // Crrate the user
        $admin = $this->modelInstance::create([
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'email' => $request['email'],
            'phoneno' => $request['phoneno'],
            'is_active' => false,
            'password' => Hash::make($password),
            'role' => $request['role_id'],
            'username' => $request['email']
        ]);

        // Add Role to db
        RoleUser::create([
            'role_id' => $request['role_id'],
            'user_id' => $admin->id
        ]);

        dd('got here..');

        // Add User to tour
        $tourInstance->user_id = $admin->id;
        $tourInstance->save();

        // Send Email
        $data = [
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'password' => $request['password'],
            'email' => $request['email'],
            'username' => $request['email'],
        ];

        Mail::to($request['email'])->send(new NewAdminUserEmail($data));
        DB::commit();

        return [
            'error' => false,
            'message' => 'User Created successfully. An email has been sent to the user',
            'data' => [],
        ];
    }
}
