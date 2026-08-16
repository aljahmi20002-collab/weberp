<?php

namespace Modules\Reports\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class StockReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                            => @$this->product->id,
            "name"                          => @$this->product->name,
            "sku"                           => @$this->product->sku,
            "branch_name"                   => @$this->branch->name,
            "variation_name"                => @$this->ProductVariation->name,
            "unit_name"                     => @$this->product->unit->short_name,
            "category"                      => @$this->product->category->name,
            "sub_category"                  => @$this->product->subcategory->name,
            "purchase_price"                => @$this->ProductVariation->default_purchase_price,
            "sell_price"                    => @$this->ProductVariation->sell_price_inc_tax,
            "current_stock_selling_price"   => @number_format($this->ProductVariation->sell_price_inc_tax * $this->qty_available, 2),
            "current_stock"                 => @$this->qty_available,
        ];
    }
}
