<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Branch\Http\Resources\v1\BranchResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'date'        => dateFormat($this->date),
            'file'        => $this->uploaded_file,
            'description' => $this->description,
            'branch'      => new BranchResource($this->branch),
            'created_at'   => $this->created_at->format('d M Y'),
            'updated_at'   => $this->updated_at->format('d M Y'),
        ];
    }
}
