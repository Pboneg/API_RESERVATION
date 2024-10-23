<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'event_date' => $this->event_date,
            'max_attendees' => $this->max_attendees,
            'available_seats' => $this->available_seats,
            'reservations_closed' => $this->reservations_closed,
            'image' =>Storage::disk('public')->url($this->image),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
