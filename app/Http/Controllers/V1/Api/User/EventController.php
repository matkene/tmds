<?php

namespace App\Http\Controllers\V1\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\EventRepository;


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

            $eventInstance = $this->eventRepository->activeEvents();

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
     * Get a Particular Event details
     */
    public function showEvent(Request $request)
    {
        try {

            if (!isset($request->event_id)) {
                return JsonResponser::send(true, "Error occured. Please select an event", null, 403);
            }

            $eventInstance = $this->eventRepository->findEventById($request->event_id);

            if (!$eventInstance) {
                return JsonResponser::send(true, "Event Record not found", null, 403);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);
        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }
}
