<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    
        return [
            'total_product_count'            => $this->total_product_count,
            'total_sales_count'              => $this->total_sales_count,
            'total_service_sale_count'       => $this->total_service_sale_count,
            'total_pos_count'                => $this->total_pos_count,
            'total_purchase_count'           => $this->total_purchase_count,
            'total_purchase_return_count'    => $this->total_purchase_return_count
        ];
    }
}
