<?php

namespace Modules\Expense\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request; 
use Modules\Account\Http\Resources\v1\AccountResource;

class ExpenseResource extends JsonResource
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
                'fromAcount'                => new AccountResource($this->fromAccount),
                'to_account'                => new AccountResource($this->toAccount),
                'amount'                    => businessCurrency($this->business_id).$this->amount,
                'note'                      => $this->note,
                'document'                  => $this->document_file,
                'created_by'                => optional($this->user)->name,
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
