<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Http\Requests\CreateTourRequest;
use App\Http\Requests\UpdateTourRequest;
use Illuminate\Support\Facades\Validator;

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

            if(!isset($request->tour_id)){
                return JsonResponser::send(true, "Error occured. Please select a tour", null, 403);
            }

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

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



    public function createTour(Request $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();

            $images = [];

            $newTourInstance = $this->tourRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => "",
                "adult_price" => $request->adult_price,
                "children_price" => $request->children_price,
                "distance" => $request->distance,
                "ratings" => "5.00"
            ]);

            if  (empty($request->image)) {
                $newTourInstance->update([
                    'image'=>  null
                ]);
            } else {
                if($files=$request->file('image')){
                    foreach($files as $file){
                        $uniqueId = bin2hex(openssl_random_pseudo_bytes(9));
                        $fileExt = $file->getClientOriginalExtension();
                        $name = $uniqueId.'_'. date("Y-m-d").'_'.time().'.'.$fileExt;
                        $imageName = config('app.url').'/Tour/'. $uniqueId. '_'. date("Y-m-d"). '_' .time(). $name;
                        $file->move(('Tour/'), $imageName);
                        $images[]=$imageName;
                    }
                }
                $newTourInstance->update([
                    'image'=>  implode("|",$images),
                ]);
            }
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
     public function update(UpdateTourRequest $request)
    {
        try {

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }


            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $name = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/Tour/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $name;
                $file->move(('Tour/'), $imageName);
            }else{
                $imageName = $tourInstance->image;
            }

            $updateTourInstance = $tourInstance->update([
                "title" => $request->title,
                "description" => $request->description,
                "location" => $request->location,
                "image" => $imageName,
                "price" => $request->price,
                "distance" => $request->distance,
                "ratings" => $request->ratings
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
            $data = $tourInstance;
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
