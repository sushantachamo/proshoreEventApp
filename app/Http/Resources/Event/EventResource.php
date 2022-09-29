<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    =>$this->id,
            'title'                 => $this->title,
            'description'           => $this->description,
            'startDate'            => $this->start_date,
            'endDate'              => $this->end_date,
            'created_at'            => $this->created_at,
        ];
    }
}
