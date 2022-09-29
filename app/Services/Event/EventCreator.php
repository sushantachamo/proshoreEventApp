<?php

namespace App\Services\Event;

use App\Entities\Event\Event;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Design\DesignRepository;
use App\Repositories\Event\EventRepository;
use App\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\DatabaseManager;


/**
 *
 */
class EventCreator
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;
    /**
     *  @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * EventCreator constructor.
     * @param EventRepository $eventRepository
     * * @param DatabaseManager $databaseManager
     */
    public function __construct(EventRepository $eventRepository,DatabaseManager $databaseManager) {
        $this->eventRepository = $eventRepository;
        $this->databaseManager = $databaseManager;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->databaseManager->beginTransaction();
        try {
            $requestData = $request->all();

            $data = array(
                "title" => $requestData['title'],
                "description" => $requestData['description'],
                "start_date" => $requestData['startDate'],
                "end_date" => $requestData['endDate'],
            );

            $event = $this->eventRepository->store($data);

            $this->databaseManager->commit();
        } catch (ValidationException $exception) {
            $this->databaseManager->rollback();
            throw $exception;
        }

        return $event;
    }
}
