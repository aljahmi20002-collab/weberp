<?php

namespace Modules\Customer\Http\Resources\v1;
 
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class CustomerResource extends JsonResource
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
                'image'                     => $this->image,
                'name'                      => $this->name,
                'phone'                     => $this->phone,
                'email'                     => $this->email,
                'address'                   => $this->address,
                'opening_balance'           => $this->opening_balance,
                'balance'                   => $this->balance,
                'status'                    => __('status.'.$this->status),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
