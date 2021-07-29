<?php


namespace App\Http\Controllers\V1\Api\User;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Responser\JsonResponser;
use App\Repositories\TestimonialRepository;
use App\Http\Requests\CreateTestimonialRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    //
    protected $testimonialRepository;

    public function __construct(TestimonialRepository $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }


    /**
     * Get All Testimonial details
     */
     public function index()
    {
        try {

            $testimonialInstance = $this->testimonialRepository->allTestimonials();

            if(!$testimonialInstance){
                return JsonResponser::send(true, "Testimonial Record not found", null, 401);
            }

            return JsonResponser::send(false, "Testimonial Record found successfully.", $testimonialInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }



    /**
     * Get Booking details by Id
     */
     public function showTestimonial(Request $request)
    {
        try {

            if(!isset($request->testimonial_id)){
                return JsonResponser::send(true, "Error occured. Please select a testimonial", null, 403);
            }

            $testimonialInstance = $this->testimonialRepository->findTestimonialById($request->testimonial_id);

            if(!$testimonialInstance){
                return JsonResponser::send(true, "Testimonial Record not found", null, 401);
            }

            return JsonResponser::send(false, "Testimonial Record found successfully.", $testimonialInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }



    public function createTestimonial(CreateTestimonialRequest $request)
    {
        try {
            $userId = auth()->user()->id;

            DB::beginTransaction();

            if ($file = $request->file('image')) {
                $name = $file->getClientOriginalName();
                $uniqueId = rand(10, 100000);
                $imageName = config('app.url') . '/Testimonial/' . $uniqueId . '_'. date("Y-m-d") . '_' . time() . $name;
                $file->move(('Testimonial/'), $imageName);
            }


            $newTestimonialInstance = $this->testimonialRepository->create([
                "title" => $request->title,
                "description" => $request->description,
                "image" => $imageName,
                "rating" => $request->rating,
                "user_id" => $userId,
                "tour_id" => $request->tour_id,
                "is_active" => true

            ]);
            if(!$newTestimonialInstance){
                $error = true;
                $message = "Testimonial was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            DB::commit();
            $error = false;
            $message = "Testimonial created successfully.";
            $data = $newTestimonialInstance;
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
