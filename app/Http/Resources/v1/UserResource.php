<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Business\Http\Resources\v1\BusinessResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                "id"                => $this->id,
                "name"              => $this->name,
                "email"             => $this->email,
                "user_type"         => $this->user_type,
                "user_type_name"    => $this->usertypes,
                "role"              => optional($this->role)->name,
                "branch"            => optional($this->branch)->name,
                "designation"       => optional($this->designation)->name,
                "department"        => optional($this->department)->name,
                "phone"             => (string)$this->mobile,
                "image"             => (string)$this->image,
                "business"          => $this->ownerbusiness,
                "business_logo"     => @$this->ownerbusiness->LogoImg,
                'created_at'        => $this->created_at->format('d M Y, h:i A'),
                'updated_at'        => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
