<?php

namespace Modules\Business\Http\Resources\v1;

use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class BusinessResource extends JsonResource
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
                "name"                      => (string)$this->business_name,
                "sku_prefix"                => (string)$this->sku_prefix,
                "default_profit_percent"    => (string)$this->default_profit_percent,
                "start_date_format"         => (string) dateFormat($this->start_date),
                "start_date"                => $this->start_date,
                "currency"                  =>(string) optional($this->currency)->currency,
                "symbol"                    =>(string) optional($this->currency)->symbol,
                "image"                     => (string) $this->logo_img,
                "status"                    => (string)$this->status,
                "position"                  => (string)$this->position,
                "owner"                     => new UserResource($this->user),
                'created_at'                 => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                 => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
