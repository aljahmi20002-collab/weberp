<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAttendance extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "total_days"      => $this->total_days,
            "total_holidays"  => $this->total_holidays,
            "total_leave_days"=> $this->total_leave_days,
            // "total_pending"   => $this->total_pending,
            "total_present"   => $this->total_present,
            "total_absent"    => $this->total_absent,
        ];
    }
}
