<?php

namespace Modules\ServiceSale\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Customer\Http\Resources\v1\CustomerResource;
use Modules\Purchase\Enums\PurchasePayStatus;

class ServiceSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
 
        $payment_status = '';
        $totalPaymentAmount = $this->payments->sum('amount');
        if($totalPaymentAmount == 0):
            $payment_status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::DUE));
        elseif($totalPaymentAmount < $this->TotalSalePrice):
            $payment_status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL)).','.__('due').': '.businessCurrency($this->business_id).number_format($this->DueAmount,2);
        else:
            $payment_status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID));
        endif; 
   
        return [
                "id"                        => $this->id,
                'date'                      => Carbon::parse($this->created_at)->format('d-m-Y h:i:s'),
                'invoice_no'                => @$this->invoice_no,
                'branch_id'                 => $this->branch_id,
                'branch'                    => optional($this->branch)->name,
                'customer_type_id'          =>  @$this->customer_type,
                'customer_type'             => Config::get('pos_default.customer_type.'.@$this->customer_type),
                'customer_details'          => new CustomerResource($this->customer),
                'customer_phone'            => $this->customer_phone, 
                'payment_status'            => $payment_status,
                'shipping_status'           => __(Config::get('pos_default.shpping_status.'.$this->shipping_status)),
                'total_sell_price_currency' => @businessCurrency($this->business_id) .' '.@number_format($this->total_sale_price,2),
                'total_sell_price'          => @$this->total_sale_price,
                'total_paid_amount'         => $this->payments->sum('amount'),
                'total_due_amount'          => $this->DueAmount,
                'currency_symbol'           => @businessCurrency($this->business_id),
                'created_by'                => optional($this->user)->name,
                'items'                     => ServiceSaleItemsResource::collection($this->saleItems),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
