<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => encryptValue($this->id),
            'teacher_id' => $this->whenHas('teacher_id', $this->teacher_id),
            'ip' => $this->whenHas('ip', $this->ip),
            'is_read' => $this->whenHas('is_read', $this->is_read),
            'description' => $this->whenHas('description', $this->description),
            'status' => $this->whenHas('status', $this->status),
            'encStatus' => $this->whenHas('status', encryptStaticValue($this->status->value)),
            'created_at' => $this->whenHas('created_at', $this->created_at),
            'is_special' => $this->whenHas('is_special', $this->is_special),
            'teacher_first_name' => $this->whenHas('teacher', $this->teacher->first_name),
            'teacher_last_name' => $this->whenHas('teacher', $this->teacher->last_name),
            'teacher_national_code' => $this->whenHas('teacher', $this->teacher->national_code),
            'teacher' => $this->whenLoaded('teacher', function () {
                return new TeacherResource($this->teacher);
            }),
        ];
    }
}
