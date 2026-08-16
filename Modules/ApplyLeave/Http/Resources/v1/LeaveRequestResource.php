<?php

namespace Modules\ApplyLeave\Http\Resources\v1;
 
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class LeaveRequestResource extends JsonResource
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
                "applicant_name"            => @$this->user->name,
                "applicant_image"           => @$this->user->image,
                "applicant_email"           => @$this->user->email,
                "applicant_phone"           => @$this->user->phone,
                "leave_type"                => @$this->leaveType->name,
                "leave_from"                => @dateFormat2($this->leave_from),
                "leave_to"                  => @dateFormat2($this->leave_to),
                "file"                      => @$this->file_path,
                "reason"                    => @$this->reason,
                "status"                    => __('leave_status.'.$this->status),
                "submitted"                 => @dateFormat($this->created_at),
                'created_at'                => $this->created_at->format('d M Y, h:i A'),
                'updated_at'                => $this->updated_at->format('d M Y, h:i A'),
            ];
    }

}
