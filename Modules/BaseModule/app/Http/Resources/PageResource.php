<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => encryptValue($this->id),
            'member_id' => $this->whenHas('member_id', encryptValue($this->member_id)),
            'member_type' => $this->member_type,
            'ip' => $this->whenHas('ip', $this->ip),
            'user_agent' => $this->whenHas('user_agent', $this->user_agent),
            'user_agent_os' => $this->user_agent ? parsedUserAgentInfo($this->user_agent)['os'] : null,
            'user_agent_browser' => $this->user_agent ? parsedUserAgentInfo($this->user_agent)['browser'] : null,
            'user_agent_platform' => $this->user_agent ? parsedUserAgentInfo($this->user_agent)['platform'] : null,
            'type' => $this->whenHas('type', $this->type),
            'created_at' => $this->whenHas('created_at', $this->created_at),

//            'member' => $this->whenLoaded('member', function () {
//                return new TeacherResource($this->member);
//            }),
        ];
    }
}
