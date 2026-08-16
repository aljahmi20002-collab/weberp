<?php

namespace Modules\Reports\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfitLossResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    { 

         return [
            //sales
            'total_sales_price'               => number_format($this->total_sales_price,2),
            'total_sale_tax_amount'           => number_format($this->total_sale_tax_amount,2),
            'total_sale_shipping_charge'      => number_format($this->total_sale_shipping_charge,2),
            'total_sale_discount_amount'      => number_format($this->total_sale_discount_amount,2),
            //pos
            'total_pos_sale_price'            => number_format($this->total_pos_sale_price,2),
            'total_pos_sale_tax_amount'       => number_format($this->total_pos_sale_tax_amount,2),
            'total_pos_sale_shipping_charge'  => number_format($this->total_pos_sale_shipping_charge,2),
            'total_pos_sale_discount_amount'  => number_format($this->total_pos_sale_discount_amount,2), 

             //purchase 
             'total_purchase_cost'             => number_format($this->total_purchase_cost,2),
             'total_purchase_tax_amount'       => number_format($this->total_purchase_tax_amount,2),
             //purchase return
             'total_purchase_return_price'     => number_format($this->total_purchase_return_price,2),
             'total_purchase_return_tax_amount'=> number_format($this->total_purchase_return_tax_amount,2),
              //stock transfer  
             'total_transfer_price'            => number_format($this->total_transfer_price,2),
             'total_shipping_charge'           => number_format($this->total_shipping_charge,2),
             //income and expense
             'total_income'                     => number_format($this->total_income,2),
             'total_expense'                    => number_format($this->total_expense,2),
             //gross profit
             'total_gross_profit'               => number_format($this->total_gross_profit,2),
             'total_net_profit'                 => number_format($this->total_net_profit,2),
             //profit information 
              'branch'                          => $this->branch ??''
         ];
    }
}
