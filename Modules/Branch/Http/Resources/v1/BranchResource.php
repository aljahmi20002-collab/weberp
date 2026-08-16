<?php

namespace Modules\Branch\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class BranchResource extends JsonResource
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
                "email"                     => (string)$this->email,
                "phone"                     => (string)$this->phone,
                'balance'                   => $this->balance,
                'website'                   => (string) $this->website,
                'country_id'                => $this->country,
                'state'                     => (string) $this->state,
                'city'                      => (string) $this->city,
                'zip_code'                  => (string) $this->zip_code,
                "status"                    => (string) $this->status,
                "status_name"               => (string) __('status.'.$this->status),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
