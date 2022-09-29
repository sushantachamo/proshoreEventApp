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
            'title'                 => $this->title,
            'description'           => $this->description,
            'start_date'            => $this->start_date,
            'end_date'              => $this->end_date,
            'created_at'            => $this->created_at,
        ];
    }
}