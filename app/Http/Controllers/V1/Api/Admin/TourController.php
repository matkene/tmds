<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use App\Models\Booking;
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



    public function createTour(CreateTourRequest $request)
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
                    $imageName = config('app.url').'/Tour/'. $uniqueId. '_'. date("Y-m-d"). '_' .time(). $name;
                    $file->move(('Tour/'), $imageName);
                    $images[]=$imageName;
                }
            }

            $newTourInstance = $this->tourRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "created_by" => $userId,
                "location" => $request->location,
                "image" => implode("|",$images),
                "adult_price" => $request->adult_price,
                "children_price" => $request->children_price,
                "distance" => $request->distance,
                "ratings" => "5.00"
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
     public function update(UpdateTourRequest $request)
    {
        try {

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }


            DB::beginTransaction();

            $images = [];

            if($files=$request->file('image')){
                foreach($files as $file){
                    $uniqueId = bin2hex(openssl_random_pseudo_bytes(9));
                    $fileExt = $file->getClientOriginalExtension();
                    $name = $uniqueId.'_'. date("Y-m-d").'_'.time().'.'.$fileExt;
                    $imageName = config('app.url').'/Tour/'. $uniqueId. '_'. date("Y-m-d"). '_' .time(). $name;
                    $file->move(('Tour/'), $imageName);
                    $images[]=$imageName;
                }
                $tourInstance->update([
                    "image" => implode("|",$images),
                ]);
            }else{
                $images = $tourInstance->image;
                $tourInstance->update([
                    "image" => $images,
                ]);
            }

            $updateTourInstance = $tourInstance->update([
                "title" => $request->title,
                "description" => $request->description,
                "created_by" => $userId,
                "location" => $request->location,
                "adult_price" => $request->adult_price,
                "children_price" => $request->children_price,
                "distance" => $request->distance,
                "ratings" => "5.00"
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

    public function activateTour(Request $request)
    {
        try {
            if(!isset($request->tour_id)){
                return JsonResponser::send(true, "Error occured. Please select a tour to activate", null, 403);
            }

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }

            $activateTour = $tourInstance->update([
                'is_active' => true
            ]);

            return JsonResponser::send(false, "Record activated successfully", $tourInstance, 201);
        } catch (\Exception $error) {
            return JsonResponser::send(true, "Internal Server Error. Please refresh and try again", null, 401);
        }

    }

    public function deactivateTour(Request $request)
    {
        try {
            if(!isset($request->tour_id)){
                return JsonResponser::send(true, "Error occured. Please select a tour to activate", null, 403);
            }

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }

            $deactivateTour = $tourInstance->update([
                'is_active' => false
            ]);

            return JsonResponser::send(false, "Record deactivated successfully", $tourInstance, 201);
        } catch (\Exception $error) {
            return JsonResponser::send(true, "Internal Server Error. Please refresh and try again", null, 401);
        }

    }

    public function deleteTours(Request $request)
    {
        try {
            if(!isset($request->tour_id)){
                return JsonResponser::send(true, "Error occured. Please select a tour to activate", null, 403);
            }

            $tourId = $request->tour_id;

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if(!$tourInstance){
                return JsonResponser::send(true, "Tour Record not found", null, 401);
            }

            //check if tour has booking
            $bookings = Booking::where('tour_id', $tourId)->get();

            if(count($bookings) > 0){
                return JsonResponser::send(true, "Record cannot be deleted because it has been attached to booking.", null, 401);
            }

            $deleteTour = $tourInstance->delete();

            return JsonResponser::send(false, "Record deleted successfully", [], 200);
        } catch (\Exception $error) {
            return JsonResponser::send(true, "Internal Server Error. Please refresh and try again", null, 401);
        }
    }

}
