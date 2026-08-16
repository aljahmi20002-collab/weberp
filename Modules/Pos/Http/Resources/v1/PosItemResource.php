<?php

namespace Modules\Pos\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request; 

class PosItemResource extends JsonResource
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
                'vari_loc_det_id'           => @$this->variation_location->ProductVariation->name,
                'sale_quantity'             => $this->sale_quantity,
                'unit_price'                => $this->unit_price,
                'total_unit_price'          => $this->total_unit_price,
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

 


}
