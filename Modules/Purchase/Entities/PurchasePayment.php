<?php

namespace Modules\Purchase\Entities;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\BankTransaction;

class PurchasePayment extends Model
{
    use HasFactory;

    protected $fillable = [];
    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
    public function upload(){
        return $this->belongsTo(Upload::class,'document_id','id');
    }
    public function getDocumentsAttribute()
    {
        if (!empty($this->upload->original['original']) && file_exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return '#';
    }

    public function bankTransactions(){
        return $this->hasMany(BankTransaction::class,'pur_pay_id','id');
    }

}
