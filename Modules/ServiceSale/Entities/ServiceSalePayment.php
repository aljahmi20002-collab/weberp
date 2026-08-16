<?php

namespace Modules\ServiceSale\Entities;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\Account\Entities\BankTransaction;
use Modules\ServiceSale\Entities\ServiceSale;
class ServiceSalePayment extends Model
{
    use HasFactory;

    protected $fillable = [];
   
    public function sale(){
        return $this->belongsTo(ServiceSale::class,'service_sale_id','id');
    }
    public function upload(){
        return $this->belongsTo(Upload::class,'document_id','id');
    }
    public function getDocumentsAttribute()
    {
        if (!empty($this->upload->original['original']) && File::exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return '#';
    }
 
    public function bankTransactions(){
        return $this->hasMany(BankTransaction::class,'ser_sale_pay_id','id');
    }
   
}
