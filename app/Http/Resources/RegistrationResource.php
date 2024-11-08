<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'course' => new CourseResource($this->whenLoaded('course')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'registration_date' => $this->registration_date?->format('Y-m-d'),
            'fees_total' => $this->fees_total,
            'fees_paid' => $this->fees_paid,
            'fees_remaining' => $this->fees_total - $this->fees_paid,
            'time_slot' => $this->time_slot,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString()
        ];
    }
} 