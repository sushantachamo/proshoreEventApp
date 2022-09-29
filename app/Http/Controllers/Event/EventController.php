<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Event\EventGetter;
use App\Services\Event\EventCreator;
use App\Services\Event\EventUpdater;
use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Http\Resources\Event\EventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Validator;


class EventController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param EventGetter $eventGetter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, EventGetter $eventGetter)
    {
        // qt1jWsNQxhohMq7AM1BPyhvMG16cQX9ZF4K7cgjz
        return EventResource::collection($eventGetter->getPaginatedList($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventCreateRequest $request
     * @param EventCreator $eventCreator
     * @return JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(Request $request, EventCreator $eventCreator)
    {
        $validator = Validator::make(request()->all(),
            [
                'title'             => 'required|max:255',
                'description'       => 'required',
                'startDate'         => 'required|date|after:today',
                'endDate'           => 'required|date|after:startDate',
            ],
            [
                'title.required'                => 'Event Title is required.',
                'title.max'                     => 'Event Title must not be greater than 255 characters.',
                'description.required'          => 'Event Description is required.',
                'startDate.required'            => 'Event Start Date is required.',
                'startDate.date'                => 'Event Start Date is not a valid date.',
                'startDate.after'               => 'Event Start Date must be a date after today',
                'endDate.required'              => 'Event End Date is required',
                'endDate.date'                  => 'Event End Date is not a valid date',
                'endDate.after'                 => 'Event End date must be a date after start date.'
            ],  
        );
    
        if($validator->fails()) {
            $errors = $validator->errors();
            return $this->errorResponse($errors);
        }
        
        $event = EventResource::make($eventCreator->store($request));

        return $this->successResponse($event, __('event.created_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param EventGetter $eventGetter
     * @return JsonResponse
     */
    public function show($id, EventGetter $eventGetter)
    {
        $event = EventResource::make($eventGetter->find($id));

        return $this->successResponse($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EventUpdateRequest $request
     * @param int $id
     * @param EventUpdater $eventUpdater
     * @return JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function update(Request $request, $id, EventUpdater $eventUpdater)
    {
        $validator = Validator::make(request()->all(),
            [
                'title'             => 'required|max:255',
                'description'       => 'required',
                'startDate'         => 'required|date|after:today',
                'endDate'           => 'required|date|after:startDate',
            ],
            [
                'title.required'                => 'Event Title is required.',
                'title.max'                     => 'Event Title must not be greater than 255 characters.',
                'description.required'          => 'Event Description is required.',
                'startDate.required'            => 'Event Start Date is required.',
                'startDate.date'                => 'Event Start Date is not a valid date.',
                'startDate.after'               => 'Event Start Date must be a date after today',
                'endDate.required'              => 'Event End Date is required',
                'endDate.date'                  => 'Event End Date is not a valid date',
                'endDate.after'                 => 'Event End date must be a date after start date.'
            ],  
        );
    
        if($validator->fails()) {
            $errors = $validator->errors();
            return $this->errorResponse($errors);
        }
        $event = $eventUpdater->update($request, $id);

        return $this->successResponse($event, __('event.updated_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param EventUpdater $eventUpdater
     * @return JsonResponse
     */
    public function destroy($id, EventUpdater $eventUpdater)
    {
        $event = $eventUpdater->delete($id);

        return $this->successResponse($event, __('event.deleted_success'));
    }

    
}
