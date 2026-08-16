<?php

namespace Modules\Supplier\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                        => $this->id, 
            'image'                     => $this->image,
            'name'                      => $this->name,
            'company_name'              => $this->company_name,
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
