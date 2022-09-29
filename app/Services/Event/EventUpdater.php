<?php

namespace App\Services\Event;

use App\Entities\Event\Event;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Design\DesignRepository;
use App\Repositories\Event\EventRepository;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventUpdater
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;


    /**
     * EventUpdater constructor.
     * @param EventRepository $eventRepository
     */
    public function __construct(
        EventRepository $eventRepository,
    ) {
        $this->eventRepository = $eventRepository;
    }


    /**
     * Update Event
     *
     * @param Request $request
     * @param $id
     * @return bool
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        $event = $this->eventRepository->find($id);

        if (!$event) {
            throw new NotFoundHttpException(__('event.not_found'));
        }

        $requestData = $request->all();

        $data = array(
            "title" => $requestData['title'],
            "description" => $requestData['description'],
            "start_date" => $requestData['startDate'],
            "end_date" => $requestData['endDate'],
        );
        
        $success = $this->eventRepository->update($id, $data);

        return $success;
    }

    /**
     * Delete Event
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $event = $this->eventRepository->find($id);

        if (!$event) {
            throw new NotFoundHttpException(__('event.not_found'));
        }

        return $event->delete($id);
    }
}
