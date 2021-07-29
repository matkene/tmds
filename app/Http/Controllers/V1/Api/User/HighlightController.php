<?php

namespace App\Http\Controllers\V1\Api\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\HighlightRepository;
use Illuminate\Support\Facades\Validator;

class HighlightController extends Controller
{
    protected $highlightRepository;

    public function __construct(HighlightRepository $highlightRepository)
    {
        $this->highlightRepository = $highlightRepository;
    }

    /**
     * Get Highlight details
     */
     public function index()
    {
        try {

            $highlightInstance = $this->highlightRepository->allHighlights();

            if(!$highlightInstance){
                return JsonResponser::send(true, "Highlight Record not found", null, 401);
            }

            return JsonResponser::send(false, "Highlight Record found successfully.", $highlightInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }


    /**
     * Get a Particular Highlight details
     */
     public function showHighlight(Request $request)
    {
        try {

            if(!isset($request->highlight_id)){
                return JsonResponser::send(true, "Error occured. Please select an Highlight", null, 403);
            }

            $highlightInstance = $this->highlightRepository->findHighlightById($request->highlight_id);

            if(!$highlightInstance){
                return JsonResponser::send(true, "Highlight Record not found", null, 401);
            }

            return JsonResponser::send(false, "Highlight Record found successfully.", $highlightInstance);

        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }



}
