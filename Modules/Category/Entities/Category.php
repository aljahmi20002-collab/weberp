<?php

namespace Modules\Category\Entities;

use App\Enums\Status;
use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Business\Entities\Business;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['id','business_id','parent_id','name','description','position','status']; 

    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }

    public function business (){
        return $this->belongsTo(Business::class,'business_id','id');
    }

    public function subcategory (){
        return $this->hasMany(static::class,'parent_id','id');
    }

    public function category(){
        return $this->belongsTo(static::class,'parent_id','id');
    }

    public function upload(){
        return $this->belongsTo(Upload::class,'image_id','id');
    }

    public function getImageAttribute()
    {
        if (!empty($this->upload->original['original']) && file_exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return static_asset('backend/images/default/blank-img.jpg');
    }

}
