<?php

namespace Modules\Supplier\Entities;

use App\Enums\Status;
use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseReturn;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [];
    
     public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
     }

     public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
     }

     public function upload(){
        return $this->belongsTo(Upload::class,'image_id','id');
    }
    public function getImageAttribute()
    {
        if (!empty($this->upload->original['original']) && file_exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return static_asset('backend/images/default/user.jpg');
    }


     public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }

    // public function purchases(){
    //     return  $this->hasMany(Purchase::class,'supplier_id','id')->where(function($query){
    //             if(isUser()):
    //                 $query->whereHas('purchaseItems',function($query){
    //                     $query->where('branch_id',Auth::user()->branch_id);
    //                 });
    //             endif;
    //     });
    // }

    // public function purchaseReturns(){
    //     return  $this->hasMany(PurchaseReturn::class,'supplier_id','id')->where(function($query){
    //             if(isUser()):
    //                 $query->whereHas('purchaseReturnItems',function($query){
    //                     $query->where('branch_id',Auth::user()->branch_id);
    //                 });
    //             endif;
    //     });
    // }
}
