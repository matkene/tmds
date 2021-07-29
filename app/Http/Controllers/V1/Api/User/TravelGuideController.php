<?php

namespace App\Http\Controllers\V1\Api\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\TravelGuideRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use Illuminate\Support\Facades\Validator;

class TravelGuideController extends Controller
{
    protected $travelGuideRepository;

    public function __construct(TravelGuideRepository $travelGuideRepository)
    {
        $this->travelGuideRepository = $travelGuideRepository;
    }

    /**
     * Get Travel Guide details
     */
     public function index()
    {
        try {

            $travelGuideInstance = $this->travelGuideRepository->allTravelGuides();

            if(!$travelGuideInstance){
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

            if(!isset($request->travelguide_id)){
                return JsonResponser::send(true, "Error occured. Please select a travel guide", null, 403);
            }

            $travelGuideInstance = $this->travelGuideRepository->findTravelGuideById($request->travelguide_id);

            if(!$travelGuideInstance){
                return JsonResponser::send(true, "Travel Guide Record not found", null, 401);
            }

            return JsonResponser::send(false, "Travel Guide Record found successfully.", $travelGuideInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }







}
