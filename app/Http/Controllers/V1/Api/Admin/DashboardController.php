<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responser\JsonResponser;
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
            $totalRevenueGenerated = $this->bookingRepository->processTotalRevenue();
            $todayRevenueGenerated = $this->bookingRepository->processTodayRevenue();

            $data = [
                "totalRevenueGenerated" => $totalRevenueGenerated,
                "todayRevenueGenerated" => $todayRevenueGenerated,
            ];

            return JsonResponser::send(false, "Dashboard data generated successfully.", $data, 200);
        } catch (\Throwable $error) {
            return JsonResponser::send(true, "Internal Server Error. Please refresh and try again.", null, 401);
        }
    }
}
