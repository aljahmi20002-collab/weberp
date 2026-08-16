<?php

namespace Modules\Account\Entities;

use App\Enums\StatementType;
use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];
    
     public function account(){
        return $this->belongsTo(Account::class, 'account_id','id');
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

     public function getMyTypeAttribute(){

         if($this->type == StatementType::INCOME):
            return '<span class="badge badge-success badge-pill">'.__(Config::get('pos_default.statement_type.'.$this->type)).'</span>';
         elseif($this->type == StatementType::EXPENSE):
            return '<span class="badge badge-danger badge-pill">'.__(Config::get('pos_default.statement_type.'.$this->type)).'</span>';
         endif;
      
     }
}
