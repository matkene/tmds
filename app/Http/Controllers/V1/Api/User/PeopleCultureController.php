<?php

namespace App\Http\Controllers\V1\Api\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\PeopleCultureRepository;
use Illuminate\Support\Facades\Validator;

class PeopleCultureController extends Controller
{
    protected $peopleCultureRepository;

    public function __construct(PeopleCultureRepository $peopleCultureRepository)
    {
        $this->peopleCultureRepository = $peopleCultureRepository;
    }

    /**
     * Get People Culture details
     */
     public function index()
    {
        try {

            $peopleCultureInstance = $this->peopleCultureRepository->allPeopleCultures();

            if(!$peopleCultureInstance){
                return JsonResponser::send(true, "People Culture Record not found", null, 401);
            }

            return JsonResponser::send(false, "People Culture found successfully.", $peopleCultureInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }


    /**
     * Get a Particular People and Culture details
     */
     public function showPeopleCulture(Request $request)
    {
        try {

            if(!isset($request->people_culture_id)){
                return JsonResponser::send(true, "Error occured. Please select a People Culture", null, 403);
            }

            $peopleCultureInstance = $this->peopleCultureRepository->findPeopleCultureById($request->people_culture_id);

            if(!$peopeleCultureInstance){
                return JsonResponser::send(true, "People Culture Record not found", null, 401);
            }

            return JsonResponser::send(false, "People Culture Record found successfully.", $peopleCultureInstance);

        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }


}
