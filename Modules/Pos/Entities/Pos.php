<?php

namespace Modules\Pos\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Modules\Customer\Entities\Customer;
use Modules\TaxRate\Entities\TaxRate;
use Modules\Pos\Entities\PosItem;
use Modules\Pos\Entities\PosPayment;
use Modules\Purchase\Enums\PurchasePayStatus;
use DNS1D;
use Modules\Sell\Enums\ShippingStatus;

class Pos extends Model
{
    use HasFactory;

    protected $fillable = [];
      
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function TaxRate (){
        return $this->belongsTo(TaxRate::class,'order_tax_id','id');
    }
    public function posItems(){ 
        return $this->hasMany(PosItem::class,'pos_id','id');
    }
 
    public function customer (){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function getTotalTaxAmountAttribute(){//total tax amount
      
        return $this->order_tax_amount;
    }


    public function getTotalSalePriceAttribute(){//total purchase return price with tax
 
        return $this->total_sell_price; 
    }
 
    //payment
    public function payments(){
        return $this->hasMany(PosPayment::class,'pos_id','id');
    }

    public function getDueAmountAttribute(){
        $paymentAmount = $this->payments->sum('amount'); 
        $dueAmount     = ($this->TotalSalePrice - $paymentAmount);
        return $dueAmount;
    }
 
    public function getMyPaymentStatusAttribute(){
        $status = '';
        $totalPaymentAmount = $this->payments->sum('amount');
        if($totalPaymentAmount == 0):
            $status = '<span class="badge badge-pill badge-danger">'.__(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::DUE)).'</span>';
        elseif($totalPaymentAmount < $this->TotalSalePrice):
            $status = '<span class="badge badge-pill badge-warning">'.__(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL)).'</span><br>'.__('due').': '.businessCurrency($this->business_id).number_format($this->DueAmount);
        else:
            $status = '<span class="badge badge-pill badge-success">'.__(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID)).'</span>';
        endif; 
        return $status;
    }


    public function getMyPaymentStatusTextAttribute(){
        $status = '';
        $totalPaymentAmount = $this->payments->sum('amount');
        if($totalPaymentAmount == 0):
            $status =  __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::DUE));
        elseif($totalPaymentAmount < $this->TotalSalePrice):
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PARTIAL)).', '.__('due').': '.businessCurrency($this->business_id).number_format($this->DueAmount);
        else:
            $status = __(Config::get('pos_default.purchase.payment_status.'.PurchasePayStatus::PAID));
        endif; 
        return $status;
    }

    public function getMyShippingStatusAttribute(){
        $status = ''; 
        if($this->shipping_status == ShippingStatus::DELIVERED):
            $status = '<span class="badge badge-pill badge-success">'.__(Config::get('pos_default.shpping_status.'.$this->shipping_status)).'</span>';
        elseif($this->shipping_status == ShippingStatus::ORDERED):
            $status = '<span class="badge badge-pill badge-warning">'.__(Config::get('pos_default.shpping_status.'.$this->shipping_status)).'</span>';
        elseif($this->shipping_status == ShippingStatus::PACKED):
            $status = '<span class="badge badge-pill badge-dark">'.__(Config::get('pos_default.shpping_status.'.$this->shipping_status)).'</span>';
        elseif($this->shipping_status == ShippingStatus::SHIPPED):
            $status = '<span class="badge badge-pill badge-primary">'.__(Config::get('pos_default.shpping_status.'.$this->shipping_status)).'</span>';
        elseif($this->shipping_status == ShippingStatus::CANCELLED):
            $status = '<span class="badge badge-pill badge-danger">'.__(Config::get('pos_default.shpping_status.'.$this->shipping_status)).'</span>';
        endif;   
        return $status;
    }


    public function getBarcodeAttribute()
    {  
       return DNS1D::getBarcodeHTML($this->invoice_no, Config::get('pos_default.barcode_types.'.$this->business->barcode_type));
    }
}
