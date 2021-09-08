<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Highlight;


class HighlightRepository
{

    private $modelInstance;

    public function __construct(Highlight $highlight)
    {
        $this->modelInstance = $highlight;
    }

    public function allHighlights()
    {
        return $this->modelInstance::with('user')
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(30);
    }

    public function findHighlightById($id)
    {
        return $this->modelInstance::with('user')
            ->whereId($id)
            ->first();
    }

    public function create($dataToCreate)
    {
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }
}
