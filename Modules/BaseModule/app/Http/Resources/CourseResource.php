<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => encryptValue($this->id),
            'title' => $this->whenHas('title', $this->title),
            'code' => $this->whenHas('code', $this->code),
            'created_at' => $this->whenHas('created_at', $this->created_at),
//            'educationalGroup' => $this->whenLoaded('educationalGroup'),
//            'educationalGrade' => $this->whenLoaded('educationalGrade')
        ];
    }
}
