<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\CreateEventRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Responser\JsonResponser;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Validator;
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

    public function createEvent(CreateEventRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();
            $newEventInstance = $this->eventRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "tags" => $request->tags,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => $request->image,
                "guest" => $request->guest,
                "is_active" => true,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date
            ]);
            if(!$newEventInstance){
                $error = true;
                $message = "Event was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
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
     public function update(CreateEventRequest $request)
    {
        try {
           $userId = auth()->user()->id;

            $eventInstance = $this->eventRepository->findEventById($request->id);

            if(!$eventInstance){
                return JsonResponser::send(true, "Event Record not found", null, 401);
            }

            /*
            $validate = $this->validateUser($request);

            if ($validate->fails()) {
                return JsonResponser::send(true, 'Validation Failed', $validate->errors()->all());
            }
            */


            DB::beginTransaction();
            $updateEventInstance = $eventInstance->update([
                "description" => $request->description,
                "tags" => $request->tags,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => $request->image,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date
            ]);
            if(!$updateEventInstance){
                $error = true;
                $message = "Event was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "Event updated successfully.";
            $data = $updateEventInstance;
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
