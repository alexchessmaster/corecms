<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
            'content' => $this->content,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'stars' => $this->stars,
        ];
    }
}
