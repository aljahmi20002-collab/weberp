<?php

namespace Modules\Purchase\Entities;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\BankTransaction;
use Modules\Purchase\Entities\PurchaseReturn;
class PurchaseReturnPayment extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function purchasereturn(){
        return $this->belongsTo(PurchaseReturn::class,'purchase_return_id','id');
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
        return $this->hasMany(BankTransaction::class,'pur_re_pay_id','id');
    }
}
