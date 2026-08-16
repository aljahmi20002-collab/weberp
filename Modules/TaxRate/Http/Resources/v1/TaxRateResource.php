<?php

namespace Modules\TaxRate\Http\Resources\v1;
 
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
 
class TaxRateResource extends JsonResource
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
                'name'        => $this->name, 
                'tax_rate'    => $this->tax_rate,
                'position'    => $this->position,
                'status'      => __('status.'.$this->status),
                'created_at'  => $this->created_at->format('d M Y, h:i A'),
                'updated_at'  => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
