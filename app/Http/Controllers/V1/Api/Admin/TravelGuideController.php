<?php

namespace App\Http\Controllers\V1\Api\Admin;

use App\Helpers\ProcessAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\TravelGuideRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Http\Requests\CreateTravelGuideRequest;
use App\Http\Requests\UpdateTravelGuideRequest;
use Illuminate\Support\Facades\Validator;

class TravelGuideController extends Controller
{
    protected $travelGuideRepository;

    public function __construct(TravelGuideRepository $travelGuideRepository)
    {
        $this->travelGuideRepository = $travelGuideRepository;
    }

    /**
     * Get travel Guide details
     */
    public function index()
    {
        try {

            $travelGuideInstance = $this->travelGuideRepository->allTravelGuides();

            if (!$travelGuideInstance) {
                return JsonResponser::send(true, "Travel Guide Record not found", null, 401);
            }

            return JsonResponser::send(false, "Travel Guide found successfully.", $travelGuideInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }


    /**
     * Get Travel Guide details by Id
     */
    public function showTravelGuide(Request $request)
    {
        try {

            if (!isset($request->travelguide_id)) {
                return JsonResponser::send(true, "Error occured. Please select a travel guide", null, 403);
            }

            $travelGuideInstance = $this->travelGuideRepository->findTravelGuideById($request->travelguide_id);

            if (!$travelGuideInstance) {
                return JsonResponser::send(true, "Travel Guide Record not found", null, 401);
            }

            return JsonResponser::send(false, "Travel Guide Record found successfully.", $travelGuideInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }



    public function createTravelGuide(CreateTravelGuideRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $name = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/TravelGuide/' . $uniqueId . '_' . date("Y-m-d") . '_' . time() . $name;
                $file->move(('TravelGuide/'), $imageName);
            }

            $newTravelGuideInstance = $this->travelGuideRepository->create([
                "title" => $request->title,
                "created_by" => $userId,
                "image" => $imageName,
                "is_active" => true,

            ]);
            if (!$newTravelGuideInstance) {
                $error = true;
                $message = "Travel Guide was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();

            $currentUserInstance = auth()->user();
            $dataToLog = [
                'causer_id' => auth()->user()->id,
                'action_id' => $newTravelGuideInstance->id,
                'action_type' => "Models\TravelGuide",
                'log_name' => "TravelGuide Created Successfully",
                'description' => "TravelGuide Created Successfully by {$currentUserInstance->lastname} {$currentUserInstance->firstname}",
            ];

            ProcessAuditLog::storeAuditLog($dataToLog);

            $error = false;
            $message = "Travel Guide created successfully.";
            $data = $newTravelGuideInstance;
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
     * Edit Travel Guide
     */
    public function update(UpdateTravelGuideRequest $request)
    {
        try {

            $travelGuideInstance = $this->travelGuideRepository->findTravelGuideById($request->travelguide_id);

            if (!$travelGuideInstance) {
                return JsonResponser::send(true, "Travel Guide Record not found", null, 401);
            }


            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $name = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/TravelGuide/' . $uniqueId . '_' . date("Y-m-d") . '_' . time() . $name;
                $file->move(('TravelGuide/'), $imageName);
            } else {
                $imageName = $travelGuideInstance->image;
            }

            $updateTravelGuideInstance = $travelGuideInstance->update([
                "title" => $request->title,
                "image" => $imageName

            ]);
            if (!$updateTravelGuideInstance) {
                $error = true;
                $message = "Travel Guide was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();

            $currentUserInstance = auth()->user();
            $dataToLog = [
                'causer_id' => auth()->user()->id,
                'action_id' => $updateTravelGuideInstance->id,
                'action_type' => "Models\TravelGuide",
                'log_name' => "TravelGuide Updated Successfully",
                'description' => "TravelGuide Updated Successfully by {$currentUserInstance->lastname} {$currentUserInstance->firstname}",
            ];

            ProcessAuditLog::storeAuditLog($dataToLog);

            $error = false;
            $message = "Travel Guide updated successfully.";
            $data = $travelGuideInstance;
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
