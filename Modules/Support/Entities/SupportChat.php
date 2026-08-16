<?php

namespace Modules\Support\Entities;

use App\Enums\UserType;
use App\Models\Upload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\Support\Entities\Support;
class SupportChat extends Model
{
    use HasFactory;

    protected $fillable = [];
   
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function support(){
        return $this->belongsTo(Support::class,'support_id','id');
    }
    public function attachedFile(){
        return $this->belongsTo(Upload::class,'attached_file','id');
    }

    public function getDownloadfileAttribute(){
      
        if($this->attachedFile && File::exists(public_path($this->attachedFile->original['original']))):
            return static_asset($this->attachedFile->original['original']);
        else:
            return null;
        endif;
    }
    public function getUserTypeAttribute(){
        if($this->user->user_type == UserType::ADMIN):
            $user_type  = __('admin');
        else:
            $user_type  = __('user');
        endif;
        return $user_type;
    }

    public function getCreatedDateTimeAttribute(){
        return Carbon::parse($this->created_at)->format('d M Y H:i a');
    }


}
