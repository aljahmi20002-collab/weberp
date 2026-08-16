<?php

namespace Modules\Purchase\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config; 
use Modules\Purchase\Enums\PurchasePayStatus;

class ReturnInvoiceResource extends JsonResource
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
        elseif($totalPaymentAmount < $this->TotalPurchaseReturnPrice):
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL));
        else:
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID));
        endif;  
  
        return [
                "id"                        => $this->id,  
                'date'                      => \Carbon\Carbon::parse($this->created_at)->format('d-m-Y'),
                'invoice_no'                => $this->return_no,
                'branches'                  =>  $this->PurchasedReturnBranch, 
                'supplier_name'             => optional($this->supplier)->name,
                'supplier_company_name'     => optional($this->supplier)->company_name,
                'supplier_email'            => optional($this->supplier)->email,
                'supplier_phone'            => optional($this->supplier)->phone,
                'supplier_address'          => optional($this->supplier)->address, 
                'payment_status'            => $status,
                'total'                     => @businessCurrency($this->business_id).''. @number_format($this->total_purchase_return_price,2),
                'paid'                      => @businessCurrency($this->business_id).''. @number_format($this->payments->sum('amount'),2),
                'due'                       => @businessCurrency($this->business_id).''. number_format($this->DueAmount,2),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

 


}
