<?php
namespace Modules\Purchase\Repositories\PurchaseReturn;

use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Modules\Product\Entities\VariationLocationDetails;
use Modules\Purchase\Entities\PurchaseReturn;
use Modules\Purchase\Entities\PurchaseReturnItem;
use Modules\Purchase\Repositories\PurchaseReturn\PurchaseReturnInterface;
use Illuminate\Support\Str;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Purchase\Entities\PurchaseReturnPayment;
use Modules\Purchase\Enums\PaymentMethod;
use Modules\Supplier\Repositories\SupplierInterface;
use Modules\TaxRate\Entities\TaxRate;

class PurchaseReturnRepository implements PurchaseReturnInterface {
    protected $model,$upload,$supplierRepo;
    public function __construct(PurchaseReturn $model,UploadInterface $upload,SupplierInterface $supplierRepo)
    {
        $this->model        = $model;
        $this->upload       = $upload;
        $this->supplierRepo = $supplierRepo;
    }
    public function get(){ 
        return $this->model::with('supplier','TaxRate','purchaseReturnItems','payments')->where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->whereHas('purchaseReturnItems',function($query){
                    $query->where('branch_id',Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    
    public function getAllPurchaseReturn(){ 
        return $this->model::with('supplier','TaxRate','purchaseReturnItems','payments','user')->where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->whereHas('purchaseReturnItems',function($query){
                    $query->where('branch_id',Auth::user()->branch_id);
                });
            endif;
        })->orderByDesc('id')->get();
    }
    
    public function getInvoice(){ 
        return $this->model::with('supplier','TaxRate','purchaseReturnItems','payments')->where(function($query){
            if(business()):
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->where('business_id',business_id());
                $query->whereHas('purchaseReturnItems',function($query){
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
            $purchaseReturn                  = new $this->model();
            $purchaseReturn->business_id     = business_id();
            $purchaseReturn->supplier_id     = $request->supplier_id;
            $purchaseReturn->return_no       = $this->returnNo(); 
            $purchaseReturn->tax_id          = $request->tax_id;   

            $taxRate                             = TaxRate::find($request->tax_id); 
            $purchaseReturn->p_tax_percent       = @$taxRate->tax_rate;  
            $purchaseReturn->total_price         = $request->total_price;
            $purchaseReturn->p_tax_amount        = $request->total_tax_amount;
            $purchaseReturn->total_buy_cost      = $request->total_buy_cost;

            $purchaseReturn->created_by      = Auth::user()->id;
            $purchaseReturn->save();
            $request['purchase_return_id'] = $purchaseReturn->id?? null;
            if($purchaseReturn && $this->purchaseReturnItemStore($request)): 
                //supplier payment
                $purchaseReturnFind = $this->getFind($purchaseReturn->id);
                $supplier           = $this->supplierRepo->getFind($request->supplier_id);
                $supplier->balance  = ($supplier->balance - $purchaseReturnFind->TotalPurchaseReturnPrice);
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

    public function purchaseReturnItemStore($request){ 
        try {
          
            foreach ($request->variation_locations as $variationLocation) {
                $variationLocationDetails              = VariationLocationDetails::find($variationLocation['id']);
                $purchaseReturnItem                    = new PurchaseReturnItem();
                $purchaseReturnItem->purchase_return_id         = $request->purchase_return_id;
                $purchaseReturnItem->business_id       = business_id();
                $purchaseReturnItem->branch_id         = $variationLocationDetails->branch_id;
                $purchaseReturnItem->vari_loc_det_id   = $variationLocationDetails->id;
                $purchaseReturnItem->return_quantity   = $variationLocation['quantity'];
                $purchaseReturnItem->unit_price        = $variationLocation['unit_price'];
                $purchaseReturnItem->total_unit_price  = ($variationLocation['unit_price'] * $variationLocation['quantity']); 
                $purchaseReturnItem->save();  
                $variationLocationDetails->qty_available = ($variationLocationDetails->qty_available - $purchaseReturnItem->return_quantity);
                $variationLocationDetails->save();
            } 
            return true;
        } catch (\Throwable $th) {  
            DB::rollBack();
            return false;
        }
    }

  

    public function returnNo(){
        return date('dmyhis').Str::random(3);
    }
    public function update($id,$request){
        try {
            $purchaseReturn   = $this->getFind($id); 
            //supplier payment
            $supplier           = $this->supplierRepo->getFind($purchaseReturn->supplier_id);
            $supplier->balance  = ($supplier->balance + $purchaseReturn->TotalPurchaseReturnPrice);
            $supplier->save();
            //end supplier payment

            foreach ($purchaseReturn->purchaseReturnItems as $item) {
                $variationLocation                = VariationLocationDetails::find($item->vari_loc_det_id);
                $variationLocation->qty_available =($variationLocation->qty_available  + $item->return_quantity);
                $variationLocation->save();
                PurchaseReturnItem::destroy($item->id);
            }
 
            $purchaseReturn->supplier_id     = $request->supplier_id;  
            $purchaseReturn->tax_id          = $request->tax_id;  

            $taxRate                             = TaxRate::find($request->tax_id); 
            $purchaseReturn->p_tax_percent       = @$taxRate->tax_rate;  
            $purchaseReturn->total_price         = $request->total_price;
            $purchaseReturn->p_tax_amount        = $request->total_tax_amount;
            $purchaseReturn->total_buy_cost      = $request->total_buy_cost;
            
            $purchaseReturn->save();
            $request['purchase_return_id'] = $purchaseReturn->id?? null;
            if($purchaseReturn && $this->purchaseReturnItemStore($request)): 
                //supplier payment
                $purchaseReturnFind = $this->getFind($purchaseReturn->id);
                $supplier           = $this->supplierRepo->getFind($request->supplier_id);
                $supplier->balance  = ($supplier->balance - $purchaseReturnFind->TotalPurchaseReturnPrice);
               
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
        $purchaseReturn   = $this->getFind($id); 

        //supplier payment
        $purchaseReturnFind = $this->getFind($purchaseReturn->id);
        $supplier           = $this->supplierRepo->getFind($purchaseReturn->supplier_id);
        $supplier->balance  = ($supplier->balance + $purchaseReturnFind->TotalPurchaseReturnPrice);
        $supplier->save();
        //end supplier payment

        foreach ($purchaseReturn->purchaseReturnItems as $item) {
            $variationLocation                = VariationLocationDetails::find($item->vari_loc_det_id);
            $variationLocation->qty_available =($variationLocation->qty_available  + $item->return_quantity);
            $variationLocation->save(); 
        } 

        foreach ($purchaseReturnFind->payments as $payment) {
            foreach ($payment->bankTransactions as $transaction) {
                 $account              = Account::find($transaction->account_id); 
                 $account->balance     = ($account->balance - $transaction->amount);
                 $account->save();
            }
         }
 
        return $this->model::destroy($purchaseReturn->id);
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
            $purchase_return_payment                  = new PurchaseReturnPayment();
            $purchase_return_payment->purchase_return_id     = $request->purchase_return_id;
            $purchase_return_payment->payment_method  = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $purchase_return_payment->bank_holder_name = $request->bank_holder_name;
                $purchase_return_payment->bank_account_no  = $request->bank_account_no;
            else:
                $purchase_return_payment->bank_holder_name = null;
                $purchase_return_payment->bank_account_no  = null;
            endif;
            $purchase_return_payment->amount          = $request->amount;
            $purchase_return_payment->description     = $request->description;
            $purchase_return_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $purchase_return_payment->document_id = $this->upload->upload('purchasereturn','',$request->document);
            endif;
            $purchase_return_payment->save();

            //supplier payment
            $purchaseReturnFind = $this->getFind($purchase_return_payment->purchase_return_id);
            $supplier           = $this->supplierRepo->getFind($purchaseReturnFind->supplier_id);
            $supplier->balance  = ($supplier->balance + $purchase_return_payment->amount);
            $supplier->save();
            //end supplier payment

            //to account 
            $account             = Account::find(purchaseDefaultAccount());
            $account->balance    = ($account->balance + $purchase_return_payment->amount);
            $account->save();

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::ADMIN;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->pur_re_pay_id  = $purchase_return_payment->id;
            $bankTransaction->account_id  = $account->id;
            $bankTransaction->type        = StatementType::INCOME;
            $bankTransaction->amount      = $purchase_return_payment->amount;
            $bankTransaction->note        = 'product_purchase_return_payment';
            $bankTransaction->save(); 
            //end to account
            

            return true;
        } catch (\Throwable $th) {  
            DB::rollBack();
            return false;
        }
    }

    public function getFindPayment($id){
        return PurchaseReturnPayment::find($id);
    }
    public function updatePayment($request){
        try {
            $purchase_return_payment                  = PurchaseReturnPayment::find($request->payment_id);

            //to account  
            $bankTransaction     = BankTransaction::where('pur_re_pay_id',$purchase_return_payment->id)->first(); 
            $back_account = Account::find($bankTransaction->account_id);
            $back_account->balance    = ($back_account->balance - $purchase_return_payment->amount);
            $back_account->save();
            //end to account

            //supplier payment
            $purchaseReturnFind = $this->getFind($purchase_return_payment->purchase_return_id);
            $supplier           = $this->supplierRepo->getFind($purchaseReturnFind->supplier_id);
            $supplier->balance  = ($supplier->balance - $purchase_return_payment->amount);
            $supplier->save();
            //end supplier payment

            $purchase_return_payment->purchase_return_id     = $request->purchase_return_id;
            $purchase_return_payment->payment_method  = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $purchase_return_payment->bank_holder_name = $request->bank_holder_name;
                $purchase_return_payment->bank_account_no  = $request->bank_account_no;
            else:
                $purchase_return_payment->bank_holder_name = null;
                $purchase_return_payment->bank_account_no  = null; 
            endif;
            $purchase_return_payment->amount          = $request->amount;
            $purchase_return_payment->description     = $request->description;
            $purchase_return_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $purchase_return_payment->document_id = $this->upload->upload('purchasereturn',$purchase_return_payment->document_id,$request->document);
            endif;
            $purchase_return_payment->save();

            //supplier payment
            $purchaseReturnFind = $this->getFind($purchase_return_payment->purchase_return_id);
            $supplier           = $this->supplierRepo->getFind($purchaseReturnFind->supplier_id);
            $supplier->balance  = ($supplier->balance + $purchase_return_payment->amount);
            $supplier->save();
            //end supplier payment

            //to account 
            $account = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance + $purchase_return_payment->amount);
            $account->save();

          
            $bankTransaction->amount      = $purchase_return_payment->amount; 
            $bankTransaction->save(); 
            //end to account
              
            return true;
        } catch (\Throwable $th) { 
            DB::rollBack();
            return false;
        }
    }

    public function deletePayment($id){
        $purchase_return_payment                  = PurchaseReturnPayment::find($id);
        //supplier payment
        $purchaseReturnFind = $this->getFind($purchase_return_payment->purchase_return_id);
        $supplier           = $this->supplierRepo->getFind($purchaseReturnFind->supplier_id);
        $supplier->balance  = ($supplier->balance - $purchase_return_payment->amount);
        $supplier->save();
        //end supplier payment
        $this->upload->unlinkImage($purchase_return_payment->document_id); 
 
        //to account  
        $bankTransaction              = BankTransaction::where('pur_re_pay_id',$purchase_return_payment->id)->first(); 
        if($bankTransaction):
            $account = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance - $purchase_return_payment->amount);
            $account->save(); 
            $bankTransaction->delete(); 
        endif;
        //end to account 
        return PurchaseReturnPayment::destroy($id);
    }

        //supplier purchase return
        public function supplierWisePurchaseReturn($supplier_id){
            $purchaseReturns =    PurchaseReturn::with('payments','business','TaxRate','purchaseReturnItems')->where('business_id',business_id())->where('supplier_id',$supplier_id)->where(function($query){
                                    if(isUser()):
                                        $query->whereHas('purchaseReturnItems',function($query){
                                            $query->where('branch_id',Auth::user()->branch_id);
                                        });
                                    endif;
                                })->orderByDesc('id')->get();
            $data = [];
            $data['total_purchase_return_count']  = $purchaseReturns->count();
            $data['total_amount']          = 0;
            $data['total_payments']        = 0;
            $data['total_due']             = 0;
            foreach ($purchaseReturns as $return) {
                $data['total_amount']          += $return->TotalPurchaseReturnPrice;
                $data['total_payments']        += $return->payments->sum('amount');
                $data['total_due']             += $return->DueAmount;
            } 
            return $data;
        }


    //supplier wise purchase return list
    public function supplierWisePurchaseList($supplier_id){
        return PurchaseReturn::with('purchaseReturnItems','payments','business','TaxRate')->where('business_id',business_id())->where('supplier_id',$supplier_id)->where(function($query){
                            if(isUser()):
                                $query->whereHas('purchaseReturnItems',function($query){
                                    $query->where('branch_id',Auth::user()->branch_id);
                                });
                            endif;
                        })->orderByDesc('id')->get(); 
    }

    public function supplierWisePurchaseReturnPaymentList($supplier_id){
        return PurchaseReturnPayment::where(function($query)use($supplier_id){
            $query->whereHas('purchasereturn',function($query)use($supplier_id){
                $query->where('business_id',business_id());
                $query->where('supplier_id',$supplier_id);
                if(isUser()):
                    $query->whereHas('purchaseReturnItems',function($query){
                        $query->where('branch_id',Auth::user()->branch_id); 
                    });
                endif;
            });
       })->orderByDesc('id')->get();
    } 
}