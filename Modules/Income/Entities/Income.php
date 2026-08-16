<?php

namespace Modules\Income\Entities;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\Account\Entities\Account;
use Modules\Branch\Entities\Branch;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [];
  
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id','id');
    }
  
    public function fromAccount(){
        return $this->belongsTo(Account::class, 'from_account','id');
    }
    public function toAccount(){
        return $this->belongsTo(Account::class, 'to_account','id');
    }
    public function upload(){
        return $this->belongsTo(Upload::class, 'document_id','id');
    }
    public function getDocumentFileAttribute()
    {
        if (!empty($this->upload->original['original']) && File::exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return '#';
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

}
