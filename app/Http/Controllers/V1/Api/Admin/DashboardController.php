<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responser\JsonResponser;
use App\Repositories\EventRepository;
use App\Repositories\BookingRepository;
use App\Interfaces\BookingTypeInterface;
use App\Interfaces\PaymentMethodInterface;

class DashboardController extends Controller
{
    protected $bookingRepository;
    protected $eventRepository;

    public function __construct(BookingRepository $bookingRepository, EventRepository $eventRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->eventRepository = $eventRepository;
    }

    public function index(){
        try {

            $totalRevenueGenerated = $this->bookingRepository->processTotalRevenue();
            $todayRevenueGenerated = $this->bookingRepository->processTodayRevenue();
            $totalVisit = $this->bookingRepository->processTotalVisit();
            $onlineBooking = $this->bookingRepository->processVisitType(BookingTypeInterface::ONLINE_BOOKING);
            $physicalBooking = $this->bookingRepository->processVisitType(BookingTypeInterface::IN_PERSON);
            $upcomingEvents = $this->eventRepository->processUpcomingEvents();
            $adultVisit = $this->bookingRepository->processAdultVisit();
            $childrenVisit = $this->bookingRepository->processChildrenVisit();
            $infantsVisit = $this->bookingRepository->processInfantVisit();

            $onlinePayment = $this->bookingRepository->processRevenueByPaymentMethod(PaymentMethodInterface::ONLINE_PAYMENT);
            $posPayment = $this->bookingRepository->processRevenueByPaymentMethod(PaymentMethodInterface::POS);
            $cashPayment = $this->bookingRepository->processRevenueByPaymentMethod(PaymentMethodInterface::CASH);

            $visitSummary = [
                "adultVisit" => $adultVisit,
                "childrenVisit" => $childrenVisit,
                "infantsVisit" => $infantsVisit,
            ];

            $ticketSummary = [
                "onlinePayment" => $onlinePayment,
                "posPayment" => $posPayment,
                "cashPayment" => $cashPayment
            ];

            $data = [
                "totalRevenueGenerated" => $totalRevenueGenerated,
                "todayRevenueGenerated" => $todayRevenueGenerated,
                "totalVisit" => $totalVisit,
                "onlineBooking" => $onlineBooking,
                "physicalBooking" => $physicalBooking,
                "upcomingEvents" => $upcomingEvents,
                "visitSummary" => $visitSummary,
                "ticketSummary" => $ticketSummary
            ];

            return JsonResponser::send(false, "Dashboard data generated successfully.", $data, 200);
        } catch (\Throwable $error) {
            return $error->getMessage();
            return JsonResponser::send(true, "Internal Server Error. Please refresh and try again.", null, 401);
        }
    }
}
