<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;


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
        });
    }
}
