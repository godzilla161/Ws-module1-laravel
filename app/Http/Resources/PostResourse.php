<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'datatime' => date('H:i d.m.Y', strtotime($this->created_at)),
            'anons' => $this->anons,
            'text' => $this->text,
            'tags' => is_null($this->tags) ? null : explode(',', $this->tags),
            'image' => $this->image
        ];
    }
}
