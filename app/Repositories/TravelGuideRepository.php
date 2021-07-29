<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TravelGuide;


class TravelGuideRepository
{

    private $modelInstance;

    public function __construct(TravelGuide $travelguide)
    {
        $this->modelInstance = $travelguide;
    }

    public function allTravelGuides()
    {

        return $this->modelInstance::with('user')
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(3);;
    }

    public function findTravelGuideById($id)
    {


        return $this->modelInstance::with('user')->where('id', $id)->first();
    }

    public function create($dataToCreate)
    {
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }
}
