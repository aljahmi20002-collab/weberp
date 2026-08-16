<?php

namespace Modules\FundTransfer\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Account\Http\Resources\v1\AccountResource;

class FundTransferResource extends JsonResource
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
                'from_account'              => new AccountResource($this->fromAccount),
                'to_account'                => new AccountResource($this->toAccount),
                'amount'                    => businessCurrency($this->business_id).$this->amount,
                'description'               => $this->description,
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
