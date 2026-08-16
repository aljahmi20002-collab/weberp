<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource; 
class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id'           => $this->id, 
            'project'      => new ProjectResource($this->project),
            'users'        => UserResource::collection($this->todolistAssigned),
            'title'        => $this->title,
            'description'  => $this->description,
            'date'         => dateFormat($this->date),
            'todo_file'    =>$this->file,
            'status'       => $this->MyStatusName,
            'created_at'   => $this->created_at->format('d M Y'),
            'updated_at'   => $this->updated_at->format('d M Y'),
        ];
    }
}
