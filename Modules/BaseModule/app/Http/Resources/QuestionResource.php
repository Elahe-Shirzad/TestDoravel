<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => encryptValue($this->id),
            'teacher_id' => $this->whenHas('teacher_id', encryptValue($this->teacher_id)),
            'ip' => $this->whenHas('ip', $this->ip),
            'user_agent' => $this->whenHas('user_agent', $this->user_agent),
            'user_agent_os' => $this->user_agent ? parsedUserAgentInfo($this->user_agent)['os'] : null,
            'user_agent_browser' => $this->user_agent ? parsedUserAgentInfo($this->user_agent)['browser'] : null,
            'user_agent_platform' => $this->user_agent ? parsedUserAgentInfo($this->user_agent)['platform'] : null,
            'type' => $this->whenHas('type', $this->type),
            'created_at' => $this->whenHas('created_at', $this->created_at),

            'teacher' => $this->whenLoaded('teacher', function () {
                return new TeacherResource($this->teacher);
            }),
        ];
    }
}
