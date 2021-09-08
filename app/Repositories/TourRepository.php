<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Favourite;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TourRepository
{

    private $modelInstance;

    public function __construct(Tour $tour)
    {
        $this->modelInstance = $tour;
    }

    public function allTours()
    {
        return $this->modelInstance::with('booking', 'user')
            ->orderBy('id', 'DESC')
            ->paginate(30);
    }

    public function activeTours()
    {
        return $this->modelInstance::with('booking', 'user')
            ->orderBy('id', 'DESC')
            ->paginate(30);
    }

    public function findTourById($id)
    {

        return $this->modelInstance::with('booking', 'user')->where('id', $id)->first();
    }

    public function create($dataToCreate)
    {
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }

    public function myTourHistory()
    {
        return Booking::with('tour')->whereUserId(Auth::user()->id)->orderBy('id', 'DESC')->paginate(20);
    }

    public function topAttraction()
    {
        return $this->modelInstance::where('is_top_attraction', 1)->get();
    }

    public function tourFavourite()
    {
        return Favourite::with('tour')->where('user_id', Auth::user()->id)->get();
    }
}
