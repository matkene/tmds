<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\PeopleCultureRepository;
use App\Http\Requests\UpdatePeopleCultureRequest;
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

            if (!$peopleCultureInstance) {
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

            if (!isset($request->id)) {
                return JsonResponser::send(true, "Error occured. Please select a People Culture", null, 403);
            }

            $peopleCultureInstance = $this->peopleCultureRepository->findPeopleCultureById($request->id);

            if (!$peopleCultureInstance) {
                return JsonResponser::send(true, "People Culture Record not found", null, 401);
            }

            return JsonResponser::send(false, "People Culture Record found successfully.", $peopleCultureInstance);
        } catch (\Throwable $error) {
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }


    /**
     * Edit People and Culture
     */
    public function update(UpdatePeopleCultureRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            $peopeleCultureInstance = $this->peopleCultureRepository->findPeopleCultureById($request->people_culture_id);

            if (!$peopeleCultureInstance) {
                return JsonResponser::send(true, "People Culture Record not found", null, 401);
            }

            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $name = $file->getClientOriginalName();
                //$uniqueId = rand(10, 100000);
                $imageName = "https://tdms-backend.herokuapp.com/images" . '/culture/' . $name;
                //$imageName = config('app.url') . '/Event/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $name;

                $file->move(('culture/'), $imageName);
            } else {
                $imageName = $peopeleCultureInstance->image;
            }

            $updatePeopleCultureInstance = $peopeleCultureInstance->update([
                "image" => $imageName
            ]);
            if (!$updatePeopleCultureInstance) {
                $error = true;
                $message = "People Culture was not updated successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "People Culture updated successfully.";
            $data = $updatePeopleCultureInstance;
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
