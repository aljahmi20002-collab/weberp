<?php

namespace Modules\StockTransfer\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Modules\StockTransfer\Enums\StockTransferStatus;
use Modules\StockTransfer\Entities\StockTransferItem;
class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
    public function fromBranch(){
        return $this->belongsTo(Branch::class,'from_branch','id');
    }
    public function toBranch(){
        return $this->belongsTo(Branch::class,'to_branch','id');
    } 
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function TransferItems(){
        return $this->hasMany(StockTransferItem::class,'stock_transfer_id','id');
    } 

    public function getTotalAmountAttribute(){ 
        return number_format($this->total_transfer_amount,2);
    }
 
    public function getMyStatusAttribute(){
        if($this->status == StockTransferStatus::PENDING){
            return '<span class="badge badge-pill badge-danger">'.__(Config::get('pos_default.stock_transfer_status.'.$this->status)).'</span>';
        }elseif($this->status == StockTransferStatus::IN_TRANSIT){
            return '<span class="badge badge-pill badge-warning">'.__(Config::get('pos_default.stock_transfer_status.'.$this->status)).'</span>';
        }elseif($this->status == StockTransferStatus::COMPLETED){
            return '<span class="badge badge-pill badge-success">'.__(Config::get('pos_default.stock_transfer_status.'.$this->status)).'</span>';
        }
    }

    public function getMyStatusUpdateAttribute(){
        $status =''; 
        if($this->status  == StockTransferStatus::PENDING):
            $status .= '<a  class="dropdown-item" href="'.route('stock.transfer.status.update',[$this->id,'status'=>StockTransferStatus::IN_TRANSIT]).'">'.__(Config::get('pos_default.stock_transfer_status.'.StockTransferStatus::IN_TRANSIT)).'</a>';
        elseif($this->status == StockTransferStatus::IN_TRANSIT):
            $status .= '<a   class="dropdown-item" href="'.route('stock.transfer.status.update',[$this->id,'status'=>StockTransferStatus::COMPLETED]).'">'.__(Config::get('pos_default.stock_transfer_status.'.StockTransferStatus::COMPLETED)).'</a>';
        endif;
        return $status;
    }

}
