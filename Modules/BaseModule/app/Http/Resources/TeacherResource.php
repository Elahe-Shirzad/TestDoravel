<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request): array
    {
        $file = getFile($this->avatar_id);

        return [
            'id' => encryptValue($this->id),
            'avatar_id' => $this->whenHas('avatar_id', encryptValue($this->avatar_id)),
            'avatar_url' => $file?->url ?? asset('assets/image/avatar.png'),
            'first_name' => $this->whenHas('first_name', $this->first_name),
            'last_name' => $this->whenHas('last_name', $this->last_name),
            'email' => $this->whenHas('email', $this->email),
            'full_name' => $this->full_name,
            'national_code' => $this->whenHas('national_code', $this->national_code),
            'created_at' => $this->whenHas('created_at', $this->created_at)
        ];
    }
}
