<?php

namespace Modules\Purchase\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config; 
use Modules\Purchase\Enums\PurchasePayStatus;

class PurchaseResource extends JsonResource
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
        elseif($totalPaymentAmount < $this->TotalPurchaseCost):
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL));
        else:
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID));
        endif;  
   
        return [
                "id"                        => $this->id,   
                "date"                      => Carbon::parse($this->created_at)->format('d-m-Y h:i:s'),
                "purchase_no"               => @$this->purchase_no,
                "branch"                    => $this->PurchasedBranch,
                "supplier_name"             => optional($this->supplier)->name,
                "supplier_company_name"     => optional($this->supplier)->company_name,
                "supplier_email"            => optional($this->supplier)->email,
                "supplier_phone"            => optional($this->supplier)->phone,
                "supplier_address"          => optional($this->supplier)->address,
                "purchase_status"           => __('purchase_status.'.$this->status),
                "payment_status"            => $status,
                "total_purchase_cost"       => @businessCurrency($this->business_id).@number_format($this->total_purchase_cost,2),
                "paid_amount"               => @businessCurrency($this->business_id).@number_format($this->payments->sum('amount'),2),
                "due_amount"                => @businessCurrency($this->business_id).@number_format($this->DueAmount,2),
                "received_by"               => @$this->user->name,
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

 


}
