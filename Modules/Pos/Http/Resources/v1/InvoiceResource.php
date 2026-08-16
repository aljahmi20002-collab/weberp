<?php

namespace Modules\Pos\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Account\Http\Resources\v1\AccountResource;
use Modules\Purchase\Enums\PurchasePayStatus;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $status = '';
        $totalPaymentAmount = $this->payments->sum('amount');
        if($totalPaymentAmount == 0):
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::DUE));
        elseif($totalPaymentAmount < $this->TotalSalePrice):
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL));
        else:
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID));
        endif;  
  
        return [
                "id"                        => $this->id,  
                'date'                      => \Carbon\Carbon::parse($this->created_at)->format('d-m-Y'),
                'invoice_no'                => $this->invoice_no,
                'branch'                    => optional($this->branch)->name,
                'customer_type_id'          => $this->customer_type,
                'customer_type_name'        =>  __(Config::get('pos_default.customer_type.'.@$this->customer_type)),
                'customer_name'             => optional($this->customer)->name,
                'customer_email'            => optional($this->customer)->email,
                'customer_phone'           => optional($this->customer)->phone,
                'customer_address'          => optional($this->customer)->address, 
                'payment_status'            => $status,
                'total'                     => @businessCurrency($this->business_id).''. @number_format($this->total_sale_price,2),
                'paid'                      => @businessCurrency($this->business_id).''. @number_format($this->payments->sum('amount'),2),
                'due'                       => @businessCurrency($this->business_id).''. number_format($this->DueAmount,2),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

 


}
