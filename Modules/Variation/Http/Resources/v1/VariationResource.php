<?php

namespace Modules\Variation\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class VariationResource extends JsonResource
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
                "values"                    => (string)$this->variation_values,
                "my_status"                 => (string)__('status.'.$this->status),
                "status"                    => (string)$this->status,
                "position"                  => (string)$this->position,
            ];
    }

}
