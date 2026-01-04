<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'title' => $this->title,
                'body' => $this->body,
                'auther_id' => new UserResource($this->whenLoaded('auther')),
                'created_at' => $this->created_at->format('d-m-y h-i-s'),
            ];
    }
}
