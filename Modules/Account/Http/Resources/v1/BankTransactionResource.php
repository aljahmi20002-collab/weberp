<?php

namespace Modules\Account\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class BankTransactionResource extends JsonResource
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
            'id'             => $this->id,
            'payment_method' => Config::get('pos_default.payment_gatway.'.$this->account->payment_gateway),
            'bank_name'      => @$this->account->bank_name,
            'holder_name'    => @$this->account->holder_name,
            'account_no'     => @$this->account->account_no,
            'branch_name'    => @$this->account->branch_name,
            'holder_name'    => @$this->account->holder_name,
            'mobile'         => @$this->account->mobile,
            'number_type'    => @$this->account->number_type, 
            'type'            => Config::get('pos_default.statement_type.'.$this->type),
            'amount'          => businessCurrency($this->business_id).''.$this->amount,
            'note'            => $this->note,
            'document'        => $this->document_file,
            'date'            => $this->created_at->format('d M Y, h:i A'),
            'created_at'      => $this->created_at->format('d M Y, h:i A'),
            'updated_at'      => $this->updated_at->format('d M Y, h:i A'),
        ];
    }
}
