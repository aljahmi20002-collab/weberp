<?php

namespace Modules\Account\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AccountResource extends JsonResource
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
                'payment_gateway'           => Config::get('pos_default.payment_gatway.'.$this->payment_gateway),
                'bank_name'                 => $this->bank_name,
                'holder_name'               => $this->holder_name,
                'account_no'                => $this->account_no,
                'branch_name'               => $this->branch_name,
                'mobile'                    => $this->mobile,
                'number_type'               => $this->number_type,
                'balance'                   => $this->balance,
                'is_default'                => $this->my_default,
                'status'                    => __('status.'.$this->status),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
