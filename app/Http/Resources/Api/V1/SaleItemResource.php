<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'vari_loc_det_id'           => @$this->variation_location->ProductVariation->name,
            'sale_quantity'             => $this->sale_quantity,
            'unit_price'                => $this->unit_price,
            'total_unit_price'          => $this->total_unit_price, 
            'created_at'                => $this->created_at->format('d M Y'),
            'updated_at'                => $this->updated_at->format('d M Y'),
        ];
    }
}
