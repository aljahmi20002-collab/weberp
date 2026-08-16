<?php
namespace Modules\Purchase\Repositories;

use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Repositories\PurchaseInterface;
use Illuminate\Support\Str;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariation;
use Modules\Purchase\Entities\PurchaseItem;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\Purchase\Enums\PaymentMethod;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Supplier\Repositories\SupplierInterface;
use Modules\TaxRate\Entities\TaxRate;

class PurchaseRepository implements PurchaseInterface{
    protected $model,$upload,$supplierRepo;
    public function __construct(Purchase $model,UploadInterface $upload,SupplierInterface $supplierRepo)
    {
        $this->model         = $model;
        $this->upload        = $upload;
        $this->supplierRepo  = $supplierRepo;
    }
    public function get(){
        return $this->model::where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->whereHas('purchaseItems',function($query){
                    $query->where('branch_id',Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    
    public function getAllPurchase(){
        return $this->model::with('user')->where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->whereHas('purchaseItems',function($query){
                    $query->where('branch_id',Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->get();
    }
     
    public function getInvoice(){
        return $this->model::with('supplier','TaxRate','purchaseItems','payments')->where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->whereHas('purchaseItems',function($query){
                    $query->where('branch_id',Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->get();
    }
    
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){

        try {
            $purchase                  = new $this->model();
            $purchase->business_id     = business_id();
            $purchase->supplier_id     = $request->supplier_id;
            $purchase->purchase_no     = $this->purchaseNo();
            $purchase->purchase_status = $request->purchase_status; 
            $purchase->tax_id          = $request->tax_id;   
 
            $taxRate                       = TaxRate::find($request->tax_id); 
            $purchase->p_tax_percent       = @$taxRate->tax_rate;  
            $purchase->total_price         = $request->total_price;
            $purchase->p_tax_amount        = $request->total_tax_amount;
            $purchase->total_buy_cost      = $request->total_buy_cost;

            $purchase->created_by      = Auth::user()->id;
            $purchase->save();
            $request['purchase_id'] = $purchase->id?? null;
            if($purchase && $this->purchaseItemStore($request)): 
                //supplier payment
                $purchaseFind      = $this->getFind($purchase->id);
                $supplier          = $this->supplierRepo->getFind($request->supplier_id);
                $supplier->balance = ($supplier->balance +  $purchaseFind->total_purchase_cost);
                $supplier->save();
                //end supplier payment
 
                return true;
            else:
                DB::rollBack();
                return false;
            endif;
        } catch (\Throwable $th) {  
   
            DB::rollBack();
            return false;
        }
    }

    public function purchaseItemStore($request){ 
        try {
           
            foreach ($request->variation_locations as $variationLocation) {
                $variationLocationDetails        = VariationLocationDetails::find($variationLocation['id']);
                $purchaseItem                    = new PurchaseItem();
                $purchaseItem->purchase_id       = $request->purchase_id;
                $purchaseItem->business_id       = business_id();
                $purchaseItem->branch_id         = $variationLocationDetails->branch_id;
                $purchaseItem->vari_loc_det_id   = $variationLocationDetails->id;
                $purchaseItem->purchase_quantity = $variationLocation['quantity'];
                $purchaseItem->unit_cost         = $variationLocation['unit_cost'];
                $purchaseItem->total_unit_cost   = ($variationLocation['unit_cost'] * $variationLocation['quantity']);
                $purchaseItem->profit_percent    = $variationLocation['profit_percent'];
                $purchaseItem->unit_sell_price   = $variationLocation['unit_selling_price'];
                $purchaseItem->save(); 
                if($request->purchase_status == PurchaseStatus::RECEIVED):
                $this->productvariationUpdate($purchaseItem,$variationLocationDetails); 
                endif;
            }
            return true;
        } catch (\Throwable $th) { 
            DB::rollBack();
            return false;
        }
    }

    public function productvariationUpdate($purchaseItem,$variationLocationDetails){
        try {
            $productVariation       = ProductVariation::find($variationLocationDetails->product_variation_id);
            $product                = Product::find($productVariation->product_id);
            $productVariation->default_purchase_price = $purchaseItem->unit_cost;
            $productVariation->profit_percent         = $purchaseItem->profit_percent;

            $profitamount                             = ($purchaseItem->unit_cost/100) * $purchaseItem->profit_percent;//profit amount
            $defaultSellPrice                         = ($purchaseItem->unit_cost +  $profitamount);

            $productVariation->default_sell_price     = $defaultSellPrice; 
            $tax_amount                               = ($defaultSellPrice/100) * $product->taxRate->tax_rate; 
            $sellingPriceWithTax                      = $defaultSellPrice + $tax_amount; 
            $productVariation->sell_price_inc_tax     = $sellingPriceWithTax;
            $productVariation->save();

            $variationLocationDetails->qty_available = ($variationLocationDetails->qty_available + $purchaseItem->purchase_quantity);
            $variationLocationDetails->save();
            return true;
        } catch (\Throwable $th) {  
            DB::rollBack();
            return false;
        }
    }

    public function purchaseNo(){
        return date('dmyhis').Str::random(3);
    }
    public function update($id,$request){
        try {
            $purchase   = $this->getFind($id);
             //supplier payment
            $purchaseFind      = $this->getFind($purchase->id);
            $supplier          = $this->supplierRepo->getFind($purchase->supplier_id);
            $supplier->balance = ($supplier->balance -  $purchaseFind->total_purchase_cost);
            $supplier->save();
             //end supplier payment
             foreach ($purchase->purchaseItems as $item) {
                 $variationLocation                = VariationLocationDetails::find($item->vari_loc_det_id);
                    if($purchase->purchase_status == PurchaseStatus::RECEIVED):
                        $variationLocation->qty_available =($variationLocation->qty_available  - $item->purchase_quantity);
                    endif;
                    $variationLocation->save();
                    PurchaseItem::destroy($item->id);
                }
 
            $purchase->supplier_id     = $request->supplier_id; 
            $purchase->purchase_status = $request->purchase_status; 
            $purchase->tax_id          = $request->tax_id;  

            $taxRate                       = TaxRate::find($request->tax_id); 
            $purchase->p_tax_percent       = @$taxRate->tax_rate;  
            $purchase->total_price         = $request->total_price;
            $purchase->p_tax_amount        = $request->total_tax_amount;
            $purchase->total_buy_cost      = $request->total_buy_cost;

            $purchase->save();
            $request['purchase_id'] = $purchase->id?? null;
            if($purchase && $this->purchaseItemStore($request)):
                 //supplier payment
                $purchaseFind      = $this->getFind($purchase->id);
                $supplier          = $this->supplierRepo->getFind($request->supplier_id);
                $supplier->balance = ($supplier->balance +  $purchaseFind->total_purchase_cost); 
                $supplier->save();
                //end supplier payment
                return true;
            else:
                DB::rollBack();
                return false;
            endif; 
            return true;
        } catch (\Throwable $th) {
            
            return true;
        }
    }
    public function delete($id){
        //supplier payment
        $purchaseFind      = $this->getFind($id);
        $supplier          = $this->supplierRepo->getFind($purchaseFind->supplier_id);
        $supplier->balance = ($supplier->balance -  $purchaseFind->total_purchase_cost);
        $supplier->save();
         //end supplier payment

         foreach ($purchaseFind->payments as $payment) {
            foreach ($payment->bankTransactions as $transaction) {
                 $account              = Account::find($transaction->account_id); 
                 $account->balance     = ($account->balance + $transaction->amount);
                 $account->save();
            }
         }
        //end supplier payment

        foreach ($purchaseFind->purchaseItems as $item) {
            $variationLocation                = VariationLocationDetails::find($item->vari_loc_det_id);
            if($purchaseFind->purchase_status == PurchaseStatus::RECEIVED):
                $variationLocation->qty_available =($variationLocation->qty_available  - $item->purchase_quantity);
            endif;
            $variationLocation->save(); 
        }

        return $this->model::destroy($id);
    } 

    public function statusUpdate($id,$status){
        try {
           $purchase                    = $this->getFind($id);
           if($status == PurchaseStatus::RECEIVED):
                foreach ($purchase->purchaseItems as  $purchaseItem) {
                    $variationLocationDetails        = VariationLocationDetails::find($purchaseItem->vari_loc_det_id);
                    $this->productvariationUpdate($purchaseItem,$variationLocationDetails); 
                }
           endif;
           $purchase->purchase_status   = $status;
           $purchase->save();
           return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function VariationLocationSkuFind($request){
        return VariationLocationDetails::where(function($query)use($request){ 
            $query->where('business_id',business_id()); 
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            else:
                $query->where('branch_id',$request->branch_id);
            endif;
            $query->where(function($query) use ($request){
                $query->whereHas('ProductVariation',function($query)use($request){
                    $query->where('sub_sku',$request->search);
                }); 
            }); 
        })->first();
    }

    public function VariationLocationFind($request){
        return VariationLocationDetails::where(function($query)use($request){ 
            $query->where('business_id',business_id()); 
            if(isUser()):
                $query->where('branch_id',Auth::user()->branch_id);
            else:
                $query->where('branch_id',$request->branch_id);
            endif;
            $query->where(function($query)use($request){
                $query->whereHas('ProductVariation',function($query)use($request){
                    $query->where('sub_sku','LIKE','%'.$request->search.'%');
                });
                $query->orWhereHas('product',function($query)use($request){
                    $query->where('name','LIKE','%'.$request->search.'%');
                    $query->orWhere('sku','LIKE','%'.$request->search.'%');
                });
            });

        })->limit(30)->get();
    }
    
    public function variationLocationItem($request){
        return VariationLocationDetails::with('product','ProductVariation')->find($request->variation_location_id);
    }

    public function addPayment($request){
        try {
            $purchase_payment                  = new PurchasePayment();
            $purchase_payment->purchase_id     = $request->purchase_id;
            $purchase_payment->payment_method  = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $purchase_payment->bank_holder_name = $request->bank_holder_name;
                $purchase_payment->bank_account_no  = $request->bank_account_no;
            else:
                $purchase_payment->bank_holder_name = null;
                $purchase_payment->bank_account_no  = null;
            endif;
            $purchase_payment->amount          = $request->amount;
            $purchase_payment->description     = $request->description;
            $purchase_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $purchase_payment->document_id = $this->upload->upload('purchase','',$request->document);
            endif;
            $purchase_payment->save();
            //supplier payment
            $purchaseFind      = $this->getFind($request->purchase_id);
            $supplier          = $this->supplierRepo->getFind($purchaseFind->supplier_id);
            $supplier->balance = ($supplier->balance -  $purchase_payment->amount);
            $supplier->save();
            //end supplier payment
 
            //from account 
            $account = Account::find(purchaseDefaultAccount());
            $account->balance    = ($account->balance - $purchase_payment->amount);
            $account->save();

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::ADMIN;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->pur_pay_id  = $purchase_payment->id;
            $bankTransaction->account_id  = $account->id;
            $bankTransaction->type        = StatementType::EXPENSE;
            $bankTransaction->amount      = $purchase_payment->amount;
            $bankTransaction->note        = 'product_purchase_payment';
            $bankTransaction->save(); 
            //end from account
 

            return true;
        } catch (\Throwable $th) { 
            DB::rollBack();
            return false;
        }
    }

    public function getFindPayment($id){
        return PurchasePayment::find($id);
    }
    public function updatePayment($request){
        try {
            $purchase_payment                  = PurchasePayment::find($request->payment_id);
             //supplier payment
            $purchaseFind      = $this->getFind($request->purchase_id);
            $supplier          = $this->supplierRepo->getFind($purchaseFind->supplier_id);
            $supplier->balance = ($supplier->balance +  $purchase_payment->amount);
            $supplier->save();
             //end supplier payment

            //from account 

            $bankTransaction          = BankTransaction::where('pur_pay_id',$purchase_payment->id)->first(); 

            $back_account = Account::find($bankTransaction->account_id);
            $back_account->balance    = ($back_account->balance + $purchase_payment->amount);
            $back_account->save(); 
            //end from account
 
            $purchase_payment->purchase_id     = $request->purchase_id;
            $purchase_payment->payment_method  = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $purchase_payment->bank_holder_name = $request->bank_holder_name;
                $purchase_payment->bank_account_no  = $request->bank_account_no;
            else:
                $purchase_payment->bank_holder_name = null;
                $purchase_payment->bank_account_no  = null; 
            endif;
            $purchase_payment->amount          = $request->amount;
            $purchase_payment->description     = $request->description;
            $purchase_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $purchase_payment->document_id = $this->upload->upload('purchase',$purchase_payment->document_id,$request->document);
            endif;
            $purchase_payment->save();

             //supplier payment
            $purchaseFind      = $this->getFind($request->purchase_id);
            $supplier          = $this->supplierRepo->getFind($purchaseFind->supplier_id);
            $supplier->balance = ($supplier->balance -  $purchase_payment->amount);
            $supplier->save();
            //end

            //from account transaction 
            //from account 
             $account             = Account::find($bankTransaction->account_id);
             $account->balance    = ($account->balance - $purchase_payment->amount);
             $account->save(); 
            //end from account 
            $bankTransaction->amount      = $purchase_payment->amount; 
            $bankTransaction->save();
            //end from account transaction

            return true;
        } catch (\Throwable $th) { 
            DB::rollBack();
            return false;
        }
    }

    public function deletePayment($id){
        try {      
            //supplier payment
            $purchase_payment  =  PurchasePayment::find($id);
            $purchaseFind      = $this->getFind($purchase_payment->purchase_id);
            $supplier          = $this->supplierRepo->getFind($purchaseFind->supplier_id);
            $supplier->balance = ($supplier->balance +  $purchase_payment->amount);
            $supplier->save();
            //end supplier payment 
            $this->upload->unlinkImage($purchase_payment->document_id); 
    
            //from account 
            $bankTransaction     = BankTransaction::where('pur_pay_id',$id)->first(); 
            if($bankTransaction):
            $account             = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance + $purchase_payment->amount);
            $account->save(); 
            $bankTransaction->delete();
            endif;
    
            //end from account 
            PurchasePayment::destroy($id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    //supplier wise purchase
    public function supplierWisePurchase($supplier_id){
        $purchases =    Purchase::with('purchaseItems','payments','business','TaxRate')->where('business_id',business_id())->where('supplier_id',$supplier_id)->where(function($query){
                            if(isUser()):
                                $query->whereHas('purchaseItems',function($query){
                                    $query->where('branch_id',Auth::user()->branch_id);
                                });
                            endif;
                        })->orderByDesc('id')->get();
        $data = [];
        $data['total_purchase_count']  = $purchases->count();
        $data['total_amount']          = 0;
        $data['total_payments']        = 0;
        $data['total_due']             = 0;
        foreach ($purchases as $purchase) {
            $data['total_amount']          += $purchase->TotalPurchaseCost;
            $data['total_payments']        += $purchase->payments->sum('amount');
            $data['total_due']             += $purchase->DueAmount;
        } 
        return $data;
    }
    //supplier wise purchase list
    public function supplierWisePurchaseList($supplier_id){
        return Purchase::with('purchaseItems','payments','business','TaxRate')->where('business_id',business_id())->where('supplier_id',$supplier_id)->where(function($query){
                            if(isUser()):
                                $query->whereHas('purchaseItems',function($query){
                                    $query->where('branch_id',Auth::user()->branch_id);
                                });
                            endif;
                        })->orderByDesc('id')->get(); 
    }

    public function  supplierWisePaymentList($supplier_id){
        return PurchasePayment::where(function($query)use($supplier_id){
                    $query->whereHas('purchase',function($query)use($supplier_id){
                        $query->where('business_id',business_id());
                        $query->where('supplier_id',$supplier_id);
                        if(isUser()):
                            $query->whereHas('purchaseItems',function($query){
                                $query->where('branch_id',Auth::user()->branch_id); 
                            });
                        endif;
                    });
               })->orderByDesc('id')->get();
    }
}