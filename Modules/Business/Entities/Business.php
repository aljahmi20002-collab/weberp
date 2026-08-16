<?php

namespace Modules\Business\Entities;

use App\Enums\Status;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\Currency\Entities\Currency;
use Modules\Subscription\Entities\Subscription;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function user(){
        return $this->belongsTo(User::class,'owner_id','id');
    }
    public function upload(){
        return $this->belongsTo(Upload::class,'logo','id');
    }

    public function getLogoImgAttribute(){
         
        if($this->logo && $this->upload && File::exists(public_path($this->upload->original['original']))):
            return static_asset($this->upload->original['original']);
        else:
            return settings('logo');
        endif;
    }
    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id','id');
    }

    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }

    public function subscription(){
        return $this->belongsTo(Subscription::class,'id','business_id');
    }
  
}
