<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type ?? 'assignment',
            'status' => $this->status,
            'classroom' => $this->classroom->id,
            'owner' => new UserResource($this->whenLoaded('user')),
            'meta' => [
                'grade' => data_get($this->options, 'grade', 20),
                'due_date' => data_get($this->options, 'due_date'),
                'published_at' => optional($this->published_at)->format('Y-m-d'),
                'students_count' => $this->users_count,
                'description' => $this->description
            ],
            'comments' => CommentResource::collection(
                $this->whenLoaded('comments')
            ),
            'submissions' => SubmissionResource::collection(
                $this->whenLoaded('submissions')
            ),
        ];
    }
}
