<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->whenHas('id', $this->id),
            'subject' => $this->whenHas('subject', $this->subject),
            'created_at' => $this->whenHas('created_at', $this->created_at),
            'ticketDepartment' => $this->whenLoaded('ticketDepartment'),
            'ticketCategory' => $this->whenLoaded('ticketCategory')
        ];
    }
}
