<?php

namespace Modules\Designation\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class DesignationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
                "id"                => $this->id,
                "name"              => $this->name,
                "position"          =>(string) $this->position,
                "status"            => (string)$this->status,
            ];
    }

}
