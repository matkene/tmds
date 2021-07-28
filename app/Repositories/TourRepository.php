<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tour;


class TourRepository {

    private $modelInstance;

    public function __construct(Tour $tour) {
        $this->modelInstance = $tour;
    }

    public function allTours() {

        return $this->modelInstance::with('booking','user')
        ->orderBy('id', 'DESC')
        ->paginate(3);
    }

    public function findTourById($id) {

       return $this->modelInstance::with('booking', 'user')->where('id', $id)->first();
    }

    public function create($dataToCreate){
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }



}
