<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;


class UserRepository {

    private $modelInstance;

    public function __construct(User $user) {
        $this->modelInstance = $user;
    }

    public function findById($userId){
        return $this->modelInstance::where('id', $userId)->first();
    }
}
