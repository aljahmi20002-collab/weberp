<?php

namespace Modules\Pos\Entities;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\Account\Entities\BankTransaction;
use Modules\Pos\Entities\Pos;
class PosPayment extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function pos(){
        return $this->belongsTo(Pos::class,'pos_id','id');
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
        return $this->hasMany(BankTransaction::class,'pos_pay_id','id');
    }
}
