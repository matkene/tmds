<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PeopleCulture;


class PeopleCultureRepository {

    private $modelInstance;

    public function __construct(PeopleCulture $peopleculture) {
        $this->modelInstance = $peopleculture;
    }

    public function allPeopleCultures()
    {
        return $this->modelInstance::with('user')
            ->orderBy('id', 'DESC')
            ->paginate(3);
    }

    public function findPeopleCultureById($id)
    {
        return $this->modelInstance::with('user')
            ->whereId($id)
            ->first();
    }

    public function create($dataToCreate){
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }



}
