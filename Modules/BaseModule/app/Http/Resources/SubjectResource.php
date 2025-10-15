<?php

namespace Modules\BaseModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request): array
    {

        $file = getFile($this->image_id);
        return [
            'id' => encryptValue($this->id),
            'title' => $this->whenHas('title', $this->title),
            'sort' => $this->whenHas('sort', $this->sort),
            'is_active' => $this->whenHas('is_active', $this->is_active),
            'type' => $this->whenHas('type', $this->type),
            'description' => $this->whenHas('description', $this->description),
            'parent' => $this->whenHas('parent_id', $this->subject?->title),
            'parent_id' => $this->whenHas('parent_id', encryptStaticValue($this->parent_id)),
            'image_id' => $file?->url ?? asset('assets/image/no-pic.jpg'),
            'image_url' => $file?->url ?? null,
            'image_extension' => $file?->extension ?? null,
            'image_size' => $file?->size ?? null,
            'image_name' => encryptValue($file?->id) ?? null,
        ];
    }
}
