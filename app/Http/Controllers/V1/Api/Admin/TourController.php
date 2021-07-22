<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\CreateTourRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Responser\JsonResponser;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TourController extends Controller
{
    protected $tourRepository;

    public function __construct(TourRepository $tourRepository)
    {
        $this->tourRepository = $tourRepository;
    }

    /**
     * Get tour details
     */
     public function index()
    {
        try {

            $tourInstance = $this->tourRepository->allTours();

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }

            return JsonResponser::send(false, "Tour Record found successfully.", $tourInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }


    /**
     * Get tour details by Id
     */
     public function showTour(Request $request)
    {
        try {

            $tourInstance = $this->tourRepository->findTourById($request->id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }

            return JsonResponser::send(false, "Tour Record found successfully.", $tourInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }



    public function createTour(CreateTourRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();
            $newTourInstance = $this->tourRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => $request->image,
                "price" => $request->price,
                "distance" => $request->distance
            ]);
            if(!$newTourInstance){
                $error = true;
                $message = "Tour was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "Tour created successfully.";
            $data = $newTourInstance;
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
     * Edit Tour
     */
     public function update(CreateTourRequest $request)
    {
        try {
            //$userId = auth()->user()->id;

            $tourInstance = $this->tourRepository->findTourById($request->id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }


            DB::beginTransaction();
            $updateTourInstance = $tourInstance->update([
                "title" => $request->title,
                "description" => $request->description,
                "location" => $request->location,
                "image" => $request->image,
                "price" => $request->price,
                "distance" => $request->distance
            ]);
            if(!$updateTourInstance){
                $error = true;
                $message = "Tour was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "Tour updated successfully.";
            $data = $updateTourInstance;
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
