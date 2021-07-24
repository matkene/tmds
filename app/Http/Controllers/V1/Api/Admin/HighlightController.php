<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\HighlightRepository;
use App\Http\Requests\CreateHighlightRequest;
use App\Http\Requests\UpdateHighlightRequest;
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

    public function createHighlight(CreateHighlightRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $nameImg = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/HighlightImage/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $nameImg;
                $file->move(('HighlightImage/'), $imageName);
            }
            if ($file = $request->file('video')) {
                $nameVideo = $file->getClientOriginalName();
                $uniqueId = rand(200, 900000);
                $videoName = config('app.url') . '/HighlightVideo/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $nameVideo;
                $file->move(('HighlightVideo/'), $videoName);
            }

            $newHighlightInstance = $this->highlightRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "image" => $imageName,
                "video" => $videoName,
                "slug" => $request->slug,
                "is_active" => true,
                "created_by" => $request->$userId
            ]);
            if(!$newHighlightInstance){
                $error = true;
                $message = "Highlight was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "Highlight created successfully.";
            $data = $newHighlightInstance;
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
     * Edit Highlight
     */
     public function update(UpdateHighlightRequest $request)
    {
        try {
           $userId = auth()->user()->id;

            $highlightInstance = $this->highlightRepository->findHighlightById($request->highlight_id);

            if(!$highlightInstance){
                return JsonResponser::send(true, "Highlight Record not found", null, 401);
            }

            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $nameImg = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/HighlightImg/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $nameImg;
                $file->move(('HighlightImg/'), $imageName);
            }else{
                $imageName = $highlightInstance->image;
            }

            if ($file = $request->file('video')) {
                $nameVideo = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $videoName = config('app.url') . '/HighlightVideo/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $nameImg;
                $file->move(('HighlightVideo/'), $videoName);
            }else{
                $videoName = $highlightInstance->video;
            }

            $updateHighlightInstance = $highlightInstance->update([
                "description" => $request->description,
                "created_by" => $userId,
                "image" => $imageName,
                "video" => $videoName,
                "slug" => $request->slug,
                "title" => $request->title
            ]);
            if(!$updateHighlightInstance){
                $error = true;
                $message = "Highlight was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "Highlight updated successfully.";
            $data = $highlightInstance;
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
