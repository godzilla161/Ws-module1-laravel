<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'comment_id' =>$this->id,
            'datatime' => date('H:i d.m.Y', strtotime($this->created_at)),
            'author' =>$this->author,
            'comment'=>$this->comment
        ];
    }
}
