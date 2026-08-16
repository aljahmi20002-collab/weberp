<?php

namespace Modules\Purchase\Entities;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Modules\Purchase\Enums\PurchasePayStatus;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Supplier\Entities\Supplier;
use Modules\TaxRate\Entities\TaxRate;
use DNS1D;
class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [];
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function TaxRate (){
        return $this->belongsTo(TaxRate::class,'tax_id','id');
    }
    public function purchaseItems(){
        return $this->hasMany(PurchaseItem::class,'purchase_id','id');
    }

    public function getPurchasedBranchAttribute(){
        $branch_id       = $this->purchaseItems->pluck('branch_id')->toArray();
        $uniqueBranchId  = array_unique($branch_id);
        $branches        = Branch::whereIn('id',$uniqueBranchId)->get();
        return $branches;
    }
    public function getTotalPurchaseCostAttribute(){//total purchase cost with tax  
        return $this->total_buy_cost;
    }

    public function getTotalTaxAmountAttribute(){//total purchase cost with tax 
        return $this->p_tax_amount;
    }

    public function getMyPurchaseStatusAttribute(){
        if($this->purchase_status == PurchaseStatus::PENDING){
           return '<span class="badge badge-pill badge-danger">'.__(Config::get('pos_default.purchase.purchase_status.'.$this->purchase_status)).'</span>';
        }elseif($this->purchase_status == PurchaseStatus::ORDERED){
            return '<span class="badge badge-pill badge-warning">'.__(Config::get('pos_default.purchase.purchase_status.'.$this->purchase_status)).'</span>';
        }elseif($this->purchase_status == PurchaseStatus::RECEIVED){
            return '<span class="badge badge-pill badge-success">'.__(Config::get('pos_default.purchase.purchase_status.'.$this->purchase_status)).'</span>';
        }
    }

    public function payments(){
        return $this->hasMany(PurchasePayment::class,'purchase_id','id');
    }

    public function getDueAmountAttribute(){
        $paymentAmount = $this->payments->sum('amount');
        $dueAmount     = ($this->TotalPurchaseCost - $paymentAmount);
        return $dueAmount;
    }

    public function getMyPaymentStatusAttribute(){
        $status = '';
        $totalPaymentAmount = $this->payments->sum('amount');
        if($totalPaymentAmount == 0):
            $status = '<span class="badge badge-pill badge-danger">'.__(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::DUE)).'</span>';
        elseif($totalPaymentAmount < $this->TotalPurchaseCost):
            $status = '<span class="badge badge-pill badge-warning">'.__(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL)).'</span><br>'.__('due').': '.businessCurrency($this->business_id).number_format($this->DueAmount,2);
        else:
            $status = '<span class="badge badge-pill badge-success">'.__(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID)).'</span>';
        endif; 
        return $status;
    }

    public function getBarcodeAttribute()
    {  
       
       return DNS1D::getBarcodeHTML($this->purchase_no, Config::get('pos_default.barcode_types.'.$this->business->barcode_type));
    }

}
