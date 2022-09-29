<?php

namespace App\Services\Event;

use App\Repositories\Event\EventRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EventGetter
 * @package App\Services\Event\Event
 */
class EventGetter
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * EventGetter constructor.
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Get paginated event list
     *
     * @param Request $request
     * @return LengthAwarePaginator|Collection|Model[]
     */
    public function getPaginatedList(Request $request)
    {
        return $this->eventRepository->getPaginatedList($request);
    }

    /**
     * Find event by primary key
     *
     * @param $id
     * @return Object
     */
    public function find($id)
    {
        $event = $this->eventRepository->find($id);

        if (!$event) {
            throw new NotFoundHttpException(__('event.not_found'));
        }

        return $event;
    }



}
