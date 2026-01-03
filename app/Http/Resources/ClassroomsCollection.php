<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassroomsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->collection->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
                'cover_image' => $model->cover_image_url,
                'meta' => [
                    'section' => $model->section,
                    'subject' => $model->subject,
                    'room' => $model->room,
                    'theme' => $model->theme,
                    'students_count' => $model->students_count,
                ],
                'user' => [
                    'name' => $model->user?->name
                ]
            ];
        });
    }
}
