<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;


class RegisterRepository {

    private $modelInstance;

    public function __construct(User $user) {
        $this->modelInstance = $user;
    }

    public function create($dataToCreate){
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }

    public function findById($id){
        return $this->modelInstance::where('id',$id)->first();
    }

    public function findByEmail($email){
        return $this->modelInstance::where('email',$email)->first();
    }
}
