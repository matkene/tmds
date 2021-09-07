<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Event;


class EventRepository
{

    private $modelInstance;

    public function __construct(Event $event)
    {
        $this->modelInstance = $event;
    }

    public function allEvents()
    {
        return $this->modelInstance::with('creator')
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    public function activeEvents()
    {
        return $this->modelInstance::with('creator')
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(3);
    }

    public function processEventsByStatus($eventStatus)
    {
        return $this->modelInstance::with('creator')
                                    ->where('status', $eventStatus)
                                    ->paginate(5);
    }

    public function findEventById($id)
    {
        return $this->modelInstance::with('creator')
            ->whereId($id)
            ->first();
    }

    public function create($dataToCreate)
    {
        return $this->modelInstance::firstOrCreate($dataToCreate);
    }

    public function processUpcomingEvents()
    {
        return $this->modelInstance::with('creator')
            ->where('is_active', true)
            ->orderBy('start_date', 'DESC')
            ->take(3)
            ->get();
    }
}
