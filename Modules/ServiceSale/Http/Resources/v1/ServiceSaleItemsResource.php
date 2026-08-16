<?php

namespace Modules\ServiceSale\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Customer\Http\Resources\v1\CustomerResource;
use Modules\Purchase\Enums\PurchasePayStatus;

class ServiceSaleItemsResource extends JsonResource
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
                'branch'                    => optional($this->branch)->name,
                'service'                   => optional($this->service)->name,
                'sale_quantity'             => $this->sale_quantity,
                'unit_price'                => $this->unit_price,
                'total_unit_price'          => $this->total_unit_price,
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
