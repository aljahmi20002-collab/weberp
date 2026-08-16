<?php

namespace Modules\Pos\Http\Resources\v1;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Modules\Branch\Http\Resources\v1\BranchResource;
use Modules\TaxRate\Http\Resources\v1\TaxRateResource;

class PosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'business_id'      => $this->business_id,
            'branch_id'        => $this->branch_id,
            'branch'           =>$this->branch->name,
            'customer_type'    => $this->customer_type,
            'customer_type_name' => __('CustomerType.'.$this->customer_type),
            'customer_id'      => $this->customer_id,
            'customer_phone'   => $this->customer_phone,
            'customer'         => $this->customer, 
            'invoice_no'       => $this->invoice_no,
            'discount_amount'  => $this->discount_amount,
            'order_tax_id'     => $this->order_tax_id,
            'taxrate'          => new TaxRateResource($this->TaxRate),
            'shipping_details' => $this->shipping_details,
            'shipping_address' => $this->shipping_address,
            'shipping_charge'  => $this->shipping_charge,
            'shipping_status'  => $this->shipping_status,
            'payment_status'   => @$this->MyPaymentStatusText,
            'total_sell_price' => @$this->TotalSalePrice,
            'paid'             => @businessCurrency($this->business_id).''. @number_format($this->payments->sum('amount'),2),
            'due'              => @businessCurrency($this->business_id).''. number_format($this->DueAmount,2),
            'created_by'       => @$this->user->name,
            'pos_item_list'    => PosItemResource::collection($this->posItems),
            'payment_list'     => $this->payments,
            'created_at'       => $this->created_at->format('d M Y'),
            'updated_at'       => $this->updated_at->format('d M Y'),
        ];
    }
}
