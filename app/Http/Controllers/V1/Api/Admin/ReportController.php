<?php

namespace App\Http\Controllers\V1\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responser\JsonResponser;
use App\Repositories\BookingRepository;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected $bookingRepository;
    protected $eventRepository;

    public function __construct(BookingRepository $bookingRepository, EventRepository $eventRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->eventRepository = $eventRepository;
    }

    public function getReport()
    {
        try {
            $totalRevenue = $this->bookingRepository->processTotalRevenue();
            $onlineRevenue = $this->bookingRepository->processOnlineRevenue();
            $walkInRevenue = $this->bookingRepository->processWalkInRevenue();
            $totalTickets = $this->bookingRepository->processTotalTickets();
            $onlineTickets = $this->bookingRepository->processOnlineTickets();
            $walkInTickets = $this->bookingRepository->processWalkInTickets();


            $data = [
                'totalRevenue' => $totalRevenue,
                'onlineRevenue' => $onlineRevenue,
                'walkInRevenue' => $walkInRevenue,
                'totalTickets' => $totalTickets,
                'onlineTickets' => $onlineTickets,
                'walkInTickets' => $walkInTickets,
            ];

            return JsonResponser::send(false, 'Reports Generated', $data);
        } catch (\Throwable $error) {

            return JsonResponser::send(true, "Internal Server Error. Please refresh and try again.", $error, 401);
        }
    }
}
