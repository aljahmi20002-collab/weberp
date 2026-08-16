<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoAssignedListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    { 
        return [
            'id'           => $this->todo->id,
            'project_id'   => $this->todo->project_id,
            'project_name' => optional($this->todo->project)->title, 
            'title'        => $this->todo->title,
            'description'  => $this->todo->description,
            'date'         => dateFormat($this->todo->date),
            'todo_file'    =>$this->todo->file,
            'created_at'   => $this->todo->created_at->format('d M Y'),
            'updated_at'   => $this->todo->updated_at->format('d M Y'),
        ];
    }
}
