<?php

namespace Modules\Assets\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AssetCategory\Entities\AssetCategory;
use Modules\Branch\Entities\Branch;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [];
    
     public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
     }

     public function user(){
        return $this->belongsTo(User::class,'created_by','id');
     }
     public function category(){
        return $this->belongsTo(AssetCategory::class,'asset_category_id','id');
     }
}
