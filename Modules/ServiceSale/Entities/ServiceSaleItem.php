<?php

namespace Modules\ServiceSale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Entities\Branch;
use Modules\Service\Entities\Service;

class ServiceSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [];
    public function serviceSale(){
        return $this->belongsTo(ServiceSale::class,'service_sale_id','id');
    }
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
