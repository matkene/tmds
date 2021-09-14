<?php


namespace App\Http\Controllers\V1\Api\Admin;

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

            if (!$testimonialInstance) {
                return JsonResponser::send(true, "Testimonial Record not found", null, 401);
            }

            return JsonResponser::send(false, "Testimonial Record found successfully.", $testimonialInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', $error, 500);
        }
    }

    /**
     * Get Testimonial details by Id
     */
    public function showTestimonial(Request $request)
    {
        try {

            if (!isset($request->testimonial_id)) {
                return JsonResponser::send(true, "Error occured. Please select a testimonial", null, 403);
            }

            $testimonialInstance = $this->testimonialRepository->findTestimonialById($request->testimonial_id);

            if (!$testimonialInstance) {
                return JsonResponser::send(true, "Testimonial Record not found", null, 401);
            }

            return JsonResponser::send(false, "Testimonial Record found successfully.", $testimonialInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    //activate testimonials
    public function fetchStats()
    {
        try {

            $testimonialStats = $this->testimonialRepository->fetchStats();


            return JsonResponser::send(false, "Testimonial Stats found", $testimonialStats, 200);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }
}
