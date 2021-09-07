<?php

namespace App\Http\Controllers\V1\Api\Admin;

use App\Helpers\ProcessAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\EventRepository;
use App\Interfaces\EventStatusInterface;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\Validator;

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

            if (!$eventInstance) {
                return JsonResponser::send(true, "Event Record not found", null, 401);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);
        } catch (\Throwable $error) {

            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function eventStats()
    {
        $ongoingEventInstance = $this->eventRepository->processEventsByStatus(EventStatusInterface::ONGOING);
        $upcomingEventInstance = $this->eventRepository->processEventsByStatus(EventStatusInterface::UPCOMING);
        $pastEventInstance = $this->eventRepository->processEventsByStatus(EventStatusInterface::PAST);
        $eventInstance = $this->eventRepository->allEvents();

        $data = [
            "ongoingEventInstance" => $ongoingEventInstance->count(),
            "upcomingEventInstance" => $upcomingEventInstance->count(),
            "pastEventInstance" => $pastEventInstance->count(),
            "eventInstance" => $eventInstance->count(),
        ];

        return JsonResponser::send(false, 'Data retrieved successfully', $data);

    }

    public function ongoingEvents()
    {
        try {

            $eventInstance = $this->eventRepository->processEventsByStatus(EventStatusInterface::ONGOING);

            return JsonResponser::send(false, "Record found successfully.", $eventInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function upcomingEvents()
    {
        try {

            $eventInstance = $this->eventRepository->processEventsByStatus(EventStatusInterface::UPCOMING);

            return JsonResponser::send(false, "Record found successfully.", $eventInstance);

        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function pastEvents()
    {
        try {

            $eventInstance = $this->eventRepository->processEventsByStatus(EventStatusInterface::PAST);

            return JsonResponser::send(false, "Record found successfully.", $eventInstance);

        } catch (\Throwable $error) {
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
                return JsonResponser::send(true, "Event Record not found", null, 401);
            }

            return JsonResponser::send(false, "Event Record found successfully.", $eventInstance);
        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function createEvent(CreateEventRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();

            $images = [];

            if($files=$request->file('image')){
                foreach($files as $file){
                    $uniqueId = bin2hex(openssl_random_pseudo_bytes(9));
                    $fileExt = $file->getClientOriginalExtension();
                    $name = $uniqueId.'_'. date("Y-m-d").'_'.time().'.'.$fileExt;
                    $imageName = config('app.url').'/Events/'. $uniqueId. '_'. date("Y-m-d"). '_' .time(). $name;
                    $file->move(('Events/'), $imageName);
                    $images[]=$imageName;
                }
            }

            $newEventInstance = $this->eventRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "tags" => $request->tags,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => implode("|",$images),
                "guest" => $request->guest,
                "is_active" => true,
                "status" => EventStatusInterface::Upcoming,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date
            ]);
            if (!$newEventInstance) {
                $error = true;
                $message = "Event was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();

            $currentUserInstance = auth()->user();
            $dataToLog = [
                'causer_id' => auth()->user()->id,
                'action_id' => $newEventInstance->id,
                'action_type' => "Models\Event",
                'log_name' => "Event created Successfully",
                'description' => "Event created Successfully by {$currentUserInstance->lastname} {$currentUserInstance->firstname}",
            ];

            ProcessAuditLog::storeAuditLog($dataToLog);

            $error = false;
            $message = "Event created successfully.";
            $data = $newEventInstance;
            return JsonResponser::send($error, $message, $data);
        } catch (\Throwable $th) {
            DB::rollback();
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }

    /**
     * Edit Event
     */
    public function update(UpdateEventRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            $eventInstance = $this->eventRepository->findEventById($request->event_id);

            if (!$eventInstance) {
                return JsonResponser::send(true, "Event Record not found", null, 401);
            }

            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $name = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/Event/' . $uniqueId . '_' . date("Y-m-d") . '_' . time() . $name;
                $file->move(('Event/'), $imageName);
            } else {
                $imageName = $eventInstance->image;
            }

            $updateEventInstance = $eventInstance->update([
                "description" => $request->description,
                "tags" => $request->tags,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => $imageName,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "title" => $request->title,
                "guest" => $request->guest
            ]);
            if (!$updateEventInstance) {
                $error = true;
                $message = "Event was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();

            $currentUserInstance = auth()->user();
            $dataToLog = [
                'causer_id' => auth()->user()->id,
                'action_id' => $updateEventInstance->id,
                'action_type' => "Models\Event",
                'log_name' => "Event created Successfully",
                'description' => "Event created Successfully by {$currentUserInstance->lastname} {$currentUserInstance->firstname}",
            ];

            ProcessAuditLog::storeAuditLog($dataToLog);
            $error = false;
            $message = "Event updated successfully.";
            $data = $eventInstance;
            return JsonResponser::send($error, $message, $data);
        } catch (\Throwable $th) {
            DB::rollback();
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }
}
