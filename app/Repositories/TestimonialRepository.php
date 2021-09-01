<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Testimonial;


class TestimonialRepository
{

    private $modelInstance;

    public function __construct(Testimonial $testimonial)
    {
        $this->modelInstance = $testimonial;
    }

    public function activeTestimonials()
    {
        return $this->modelInstance::with('tour', 'user')
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(5);
    }

    public function allTestimonials()
    {
        return $this->modelInstance::with('tour', 'user')
            ->orderBy('id', 'DESC')
            ->paginate(8);
    }

    public function findTestimonialById($id)
    {
        return $this->modelInstance::with('tour', 'user')->where('id', $id)->first();
    }

    public function create($dataToCreate)
    {
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }
}
