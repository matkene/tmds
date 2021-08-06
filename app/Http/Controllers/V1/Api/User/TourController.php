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

            if (!$tourInstance) {
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

            if (!isset($request->tour_id)) {
                return JsonResponser::send(true, "Error occured. Please select a tour", null, 403);
            }

            $tourInstance = $this->tourRepository->findTourById($request->tour_id);

            if (!$tourInstance) {
                return JsonResponser::send(true, "Tour Record not found", null, 403);
            }

            return JsonResponser::send(false, "Tour Record found successfully.", $tourInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function tourHistory()
    {
        try {

            $tourHistory = $this->tourRepository->myTourHistory();
            return JsonResponser::send(false, "Tour history found successfully.", $tourHistory);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function topAttraction()
    {
        try {
            $topAttraction = $this->tourRepository->topAttraction();
            return JsonResponser::send(false, "Top Tour Attraction", $topAttraction);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function tourFavourite()
    {
        try {
            $tourFavourite = $this->tourRepository->tourFavourite();
            return JsonResponser::send(false, "Favourites", $tourFavourite);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }
}
