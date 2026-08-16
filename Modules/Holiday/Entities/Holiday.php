<?php

namespace Modules\Holiday\Entities;

use App\Enums\Status;
use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ['name','from','to','note'];

   public function upload(){
       return $this->belongsTo(Upload::class, 'file','id');
   }
   public function getFilePathAttribute()
   {
       if ($this->upload && !empty($this->upload->original['original']) && File::exists(public_path($this->upload->original['original']))) {
           return static_asset($this->upload->original['original']);
       }
       return '#';
   }

   public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }

}
