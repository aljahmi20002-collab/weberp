<?php

namespace Modules\Warranties\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class WarrantiesResource extends JsonResource
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
                "duration"                  => (string)$this->duration,
                "duration_types"            => (string)$this->durationtypes,
                "description"               => (string)$this->description,
                "status"                    => (string)$this->status,
                "position"                  => (string)$this->position,
            ];
    }

}
