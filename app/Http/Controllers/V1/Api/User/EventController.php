<?php

namespace App\Http\Controllers\V1\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Responser\JsonResponser;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Get Event details
     */
     public function index()
    {
        try {

            $eventInstance = $this->eventRepository->allEvents();

            if(!$eventInstance){
                return JsonResponser::send(true, "Event Record not found", null, 401);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }


    /**
     * Get a Particular Event details
     */
     public function showEvent(Request $request)
    {
        try {

            $eventInstance = $this->eventRepository->findEventById($request->id);

            if(!$eventInstance){
                return JsonResponser::send(true, "Event Record not found", null, 401);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }




}
