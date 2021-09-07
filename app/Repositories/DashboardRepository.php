<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardRepository
{



    public function index()
    {
        $user = Auth::user();

        $totalVisits = Booking::whereUserId($user->id)->where('status', 'completed')->count();
        $totalVisitedLocation = Booking::whereUserId($user->id)->where('status', 'completed')->distinct()->count('tour_id');
        $totalTickects = Booking::whereUserId($user->id)->count();

        return [
            'total_visits' => $totalVisits,
            'total_visited_location' => $totalVisitedLocation,
            'total_tickets' => $totalTickects,
        ];
    }
}
