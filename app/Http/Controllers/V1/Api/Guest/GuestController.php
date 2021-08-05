<?php

namespace App\Http\Controllers\V1\Api\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ViewEventRequest;
use App\Http\Requests\ViewTourRequest;
use App\Http\Responser\JsonResponser;
use App\Repositories\EventRepository;
use App\Repositories\TourRepository;
use App\Repositories\PeopleCultureRepository;
use App\Repositories\TravelGuideRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\HighlightRepository;


class GuestController extends Controller
{
    protected $eventRepository;
    protected $tourRepository;
    protected $peopleCultureRepository;
    protected $testimonialRepository;
    protected $highlightRepository;
    protected $travelGuideRepository;

    public function __construct(
        EventRepository $eventRepository,
        TourRepository $tourRepository,
        PeopleCultureRepository $peopleCultureRepository,
        TestimonialRepository $testimonialRepository,
        HighlightRepository $highlightRepository,
        TravelGuideRepository $travelGuideRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->tourRepository = $tourRepository;
        $this->peopleCultureRepository = $peopleCultureRepository;
        $this->testimonialRepository = $testimonialRepository;
        $this->highlightRepository = $highlightRepository;
        $this->travelGuideRepository = $travelGuideRepository;
    }


    /**
     * Get Event details
     */
    public function listAllActiveEvents()
    {
        try {

            $eventInstance = $this->eventRepository->allEvents();

            if (!$eventInstance) {
                return JsonResponser::send(true, "Event Record not found", null, 403);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function viewSingleEvent(ViewEventRequest $request)
    {
        try {

            $eventInstance = $this->eventRepository->findEventById($request->event_id);

            if (!$eventInstance) {
                return JsonResponser::send(true, "Event Record not found", null, 403);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }



    /**
     * Get Tour details
     */
    public function listAllActiveTours()
    {
        try {

            $tourInstance = $this->tourRepository->activeTours();

            if (!$tourInstance) {
                return JsonResponser::send(true, "Tour Record not found", null, 403);
            }

            return JsonResponser::send(false, "Tour Record found successfully.", $tourInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function viewSingleTour(ViewTourRequest $request)
    {
        try {

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if (!$tourInstance) {
                return JsonResponser::send(true, "Tour Record not found", null, 403);
            }

            return JsonResponser::send(false, "Tour Record found successfully.", $tourInstance);
        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }


    /**
     * Get Peopele Culture details
     */
    public function listAllActivePeopleCultures()
    {
        try {

            $peopleCultureInstance = $this->peopleCultureRepository->allPeopleCultures();

            if (!$peopleCultureInstance) {
                return JsonResponser::send(true, "People Culture Record not found", null, 403);
            }

            return JsonResponser::send(false, "People Culture Record found successfully.", $peopleCultureInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    /**
     * Get Testimonial details
     */
    public function listAllActiveTestimonials()
    {
        try {

            $testimonialInstance = $this->testimonialRepository->allTestimonials();

            if (!$testimonialInstance) {
                return JsonResponser::send(true, "Testimonial Record not found", null, 403);
            }

            return JsonResponser::send(false, "Testimonial Record found successfully.", $testimonialInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    /**
     * Get Travel Guide details
     */
    public function listAllActiveTravelGuides()
    {
        try {

            $travelguideInstance = $this->travelGuideRepository->allTravelGuides();

            if (!$travelguideInstance) {
                return JsonResponser::send(true, "Travel Guide Record not found", null, 403);
            }

            return JsonResponser::send(false, "Travel Guide Record found successfully.", $travelguideInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }


    /**
     * Get Highlight details
     */
    public function listAllActiveHighlights()
    {
        try {

            $highlightInstance = $this->highlightRepository->allHighlights();

            if (!$highlightInstance) {
                return JsonResponser::send(true, "Highlight Record not found", null, 403);
            }

            return JsonResponser::send(false, "Highlight Record found successfully.", $highlightInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }
}
