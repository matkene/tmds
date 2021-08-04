<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BookingRepository;

class DashboardController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function index(){
        try {
            return $totalRevenueGenerated = $this->bookingRepository->processTotalRevenue();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
