<?php


namespace App\Http\Controllers\V1\Api\User;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Responser\JsonResponser;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TourController extends Controller
{
    //
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



}
