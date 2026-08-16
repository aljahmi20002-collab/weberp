<?php

namespace Modules\StockTransfer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\VariationLocationDetails;

class StockTransferItem extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function variation_location(){
        return $this->belongsTo(VariationLocationDetails::class,'vari_loc_det_id','id');
    }
}
