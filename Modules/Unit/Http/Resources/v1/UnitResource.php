<?php

namespace Modules\Unit\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
                "id"                        => $this->id,
                "name"                      => (string)$this->name,
                "short_name"                => (string)$this->short_name,
                "my_status"                 => (string)__('status.'.$this->status),
                "status"                    => (string)$this->status,
                "position"                  => (string)$this->position,
            ];
    }

}
