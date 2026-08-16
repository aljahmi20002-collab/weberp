<?php
namespace Modules\SaleProposal\Repositories;

use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Enums\CustomerType;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Product\Entities\VariationLocationDetails;
use Modules\SaleProposal\Entities\SaleProposal;
use Modules\SaleProposal\Entities\SaleProposalItem;
use Modules\SaleProposal\Repositories\SaleProposalInterface;

use Illuminate\Support\Str;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchStatement;
use Modules\Purchase\Enums\PaymentMethod;
use Modules\SaleProposal\Entities\SaleProposalPayment;

class SaleProposalRepository implements SaleProposalInterface{

    protected $model,$upload,$customerRepo;
    public function __construct(SaleProposal $model,UploadInterface $upload,CustomerInterface $customerRepo)
    {
        $this->model        = $model;
        $this->upload       = $upload;
        $this->customerRepo = $customerRepo;    
    } 
    public function get(){  
        return $this->model::with('payments','saleItems','TaxRate','customer','branch')->where(function($query){
                $query->where('business_id',business_id());
                if(isUser()):
                    $query->where('branch_id',Auth::user()->branch_id); 
                endif;
        })->orderByDesc('id')->get();
    } 
 
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){ 
        try {
           
            if(business()):
                $branch_id   = $request->branch_id;
            else:
                $branch_id   = Auth::user()->branch_id;
            endif;  
            $sale                  = new $this->model(); 
            $sale->business_id     = business_id();
            $sale->branch_id       = $branch_id;
            $sale->customer_type   = $request->customer_type;
            if($request->customer_type == CustomerType::EXISTING_CUSTOMER):
            $sale->customer_id     = $request->customer_id;
            elseif($request->customer_type == CustomerType::WALK_CUSTOMER):
                $sale->customer_phone = $request->customer_phone;
            endif;
            $sale->invoice_no         = $this->invoiceNo();
            if(!blank($request->discount_amount)):
                $sale->discount_amount = $request->discount_amount;
            endif;
            $sale->order_tax_id    = $request->tax_id;
            $sale->shipping_details= $request->shipping_details;
            $sale->shipping_address= $request->shipping_address;
            if(!blank($request->shipping_charge)):
            $sale->shipping_charge = $request->shipping_charge;
            endif;
            $sale->shipping_status = $request->shipping_status;
            $sale->created_by      = Auth::user()->id;
            $sale->save();
            if($sale): 
                $request['sale_id'] = $sale->id;
                $this->saleItem($request);
                if($request->customer_type == CustomerType::EXISTING_CUSTOMER):
                    $this->customerBalance($request);
                endif;
                return true;
            endif;
            return false; 
        } catch (\Throwable $th) {  
            return false;
        }
    }

    public function customerBalance($request){
        $sale              = $this->getFind($request->sale_id);
        $customer          = Customer::find($request->customer_id);
        $customer->balance = $customer->balance -  $sale->DueAmount;
        $customer->save();
    }

    public function saleItem($request){ 
        foreach ($request->variation_locations as $variationLocation) {            
            $variationLocationDetails   = VariationLocationDetails::find($variationLocation['id']);
            $saleItem                   = new SaleProposalItem();
            $saleItem->sale_proposal_id = $request->sale_id;
            $saleItem->business_id      = business_id();
            $saleItem->branch_id        = $variationLocationDetails->branch_id;
            $saleItem->vari_loc_det_id  = $variationLocationDetails->id;
            $saleItem->sale_quantity    = $variationLocation['quantity'];
            $saleItem->unit_price       =  $variationLocation['unit_price'];
            $saleItem->total_unit_price = ($variationLocation['unit_price'] * $variationLocation['quantity']); 
            $saleItem->save();

            $variationLocationDetails->qty_available   = ($variationLocationDetails->qty_available - $variationLocation['quantity']);
            $variationLocationDetails->save();
        }
    }

    public function invoiceNo(){
        return date('dmyhis').Str::random(3);
    }
    public function update($id,$request){
        try {

            if(business()):
                $branch_id   = $request->branch_id;
            else:
                $branch_id   = Auth::user()->branch_id;
            endif; 
 
            $sale                  = $this->getFind($id); 
             //customer balance 
             if($sale->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer            = $this->customerRepo->getFind($sale->customer_id);
                $customer->balance   = ($customer->balance + $sale->TotalSalePrice);
                $customer->save();
            endif;

            $sale->business_id     = business_id();
            $sale->branch_id       = $branch_id;
            $sale->customer_type   = $request->customer_type;
            if($request->customer_type == CustomerType::EXISTING_CUSTOMER):
            $sale->customer_id     = $request->customer_id;
            elseif($request->customer_type == CustomerType::WALK_CUSTOMER):
                $sale->customer_phone = $request->customer_phone;
            endif; 
            if(!blank($request->discount_amount)):
                $sale->discount_amount = $request->discount_amount;
            endif;
            $sale->order_tax_id    = $request->tax_id;
            $sale->shipping_details= $request->shipping_details;
            $sale->shipping_address= $request->shipping_address;
            if(!blank($request->shipping_charge)):
            $sale->shipping_charge = $request->shipping_charge;
            endif;
            $sale->shipping_status = $request->shipping_status;
            $sale->created_by      = Auth::user()->id;
            $sale->save();
            if($sale):  

                $request['sale_id']   = $sale->id;
                $request['branch_id'] = $branch_id;
                $this->saleReverse($request);
                $this->saleItem($request);

                //customer balance 
                if($sale->customer_type == CustomerType::EXISTING_CUSTOMER):
                    $findSales           = $this->getFind($sale->id);
                    $customer            = $this->customerRepo->getFind($sale->customer_id);
                    $customer->balance   = ($customer->balance - $findSales->TotalSalePrice);
                    $customer->save();
                endif;

                return true;
            endif;
            return false; 


        } catch (\Throwable $th) {
           return false;
        }
    }

    public function saleReverse($request){
        $sale            = $this->getFind($request->sale_id);
        foreach ($sale->saleItems as $item) {            
            $variationLocationDetails                  = VariationLocationDetails::find($item->vari_loc_det_id);
            $variationLocationDetails->qty_available   = ($variationLocationDetails->qty_available + $item->sale_quantity);
            $variationLocationDetails->save(); 
            SaleProposalItem::destroy($item->id);
        } 
    }
    
    public function customerBalanceReverse($sale_id){
        $sale              = $this->getFind($sale_id); 
        $customer          = Customer::find($sale->customer_id);
        $customer->balance = ($customer->balance +  $sale->TotalSalePrice) - $sale->payments->sum('amount');
        $customer->save();
    }
 
    public function delete($id){
        $sale            = $this->getFind($id);
        if($sale->customer_type == CustomerType::EXISTING_CUSTOMER): 
            $this->customerBalanceReverse($sale->id);
        endif;

        foreach ($sale->saleItems as $item) {            
            $variationLocationDetails                  = VariationLocationDetails::find($item->vari_loc_det_id);
            $variationLocationDetails->qty_available   = ($variationLocationDetails->qty_available + $item->sale_quantity);
            $variationLocationDetails->save(); 
            SaleProposalItem::destroy($item->id);
        }
        $branch           =  Branch::find($sale->branch_id);
        $branch->balance  =  $branch->balance + $sale->payments->sum('amount');
        $branch->save();

        foreach ($sale->payments as $payment) {
            foreach ($payment->bankTransactions as $transaction) {
                 $account              = Account::find($transaction->account_id); 
                 $account->balance     = ($account->balance - $transaction->amount);
                 $account->save();
            }
         }

        return $this->model::destroy($id);
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

    public function variationLocationItemFind($request){
        return VariationLocationDetails::with('product','ProductVariation')->find($request->variation_location_id);
    }
    public function variationLocationItem($request){ 
        return VariationLocationDetails::with('product','ProductVariation')->find($request->variation_location_id);
    }

    //payment
    public function addPayment($request){
       
        try {
            $sale_payment                         = new SaleProposalPayment();
            $sale_payment->sale_proposal_id       = $request->sale_id;
            $sale_payment->payment_method         = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $sale_payment->bank_holder_name = $request->bank_holder_name;
                $sale_payment->bank_account_no  = $request->bank_account_no;
            else:
                $sale_payment->bank_holder_name = null;
                $sale_payment->bank_account_no  = null;
            endif;
            $sale_payment->amount          = $request->amount;
            $sale_payment->description     = $request->description;
            $sale_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)): 
                $sale_payment->document_id = $this->upload->upload('proposal','',$request->document);
            endif; 
            $sale_payment->save();

            //payment calculation
            $saleFind         = $this->getFind($request->sale_id); 
            $branch           =  Branch::find($saleFind->branch_id);
            $branch->balance  = $branch->balance-$sale_payment->amount;
            $branch->save();
 
            //branch statements
            $branchStatement                  = new BranchStatement();
            $branchStatement->sale_proposal_id= $request->sale_id; 
            $branchStatement->sale_proposal_payment_id = $sale_payment->id; 
            $branchStatement->business_id     = business_id();
            $branchStatement->branch_id       = $saleFind->branch_id;
            $branchStatement->type            = StatementType::INCOME;
            $branchStatement->amount          = $sale_payment->amount;
            $branchStatement->note            = 'Sale proposal Income';
            $branchStatement->save(); 

            //customer balance 
            if($saleFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer = $this->customerRepo->getFind($saleFind->customer_id);
                $customer->balance = $customer->balance + $sale_payment->amount;
                $customer->save();
            endif;

            //branch expense
            //to account 
            $account             = Account::find(saleDefaultAccount($saleFind->branch_id)); 
            $account->balance    = ($account->balance + $sale_payment->amount);
            $account->save();
            //end to account

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::BRANCH;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->branch_id   = $saleFind->branch_id;   
            $bankTransaction->sale_prop_pay_id = $sale_payment->id;
            $bankTransaction->account_id  = $account->id;
            $bankTransaction->type        = StatementType::EXPENSE;
            $bankTransaction->amount      = $sale_payment->amount;
            $bankTransaction->note        = 'sale_proposal_payment';
            $bankTransaction->save(); 
            //branch expense
 
            return true;

        } catch (\Throwable $th) {  
            DB::rollBack();
            return false;
        }
    }

    public function getFindPayment($id){
        return SaleProposalPayment::find($id);
    }
    public function updatePayment($request){
        try { 
            $sale_payment                         = SaleProposalPayment::find($request->payment_id);

            $bankTransaction     = BankTransaction::where('sale_prop_pay_id',$sale_payment->id)->first();
            //to account 
            $back_account             = Account::find($bankTransaction->account_id);
            $back_account->balance    = ($back_account->balance - $sale_payment->amount);
            $back_account->save();
            //end to account

            $saleFind         = $this->getFind($request->sale_id);  
            $branch           =  Branch::find($saleFind->branch_id);
            $branch->balance  = $branch->balance + $sale_payment->amount;
            $branch->save();

            //customer balance 
            if($saleFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer = $this->customerRepo->getFind($saleFind->customer_id);
                $customer->balance = $customer->balance - $sale_payment->amount;
                $customer->save();
            endif; 

            $sale_payment->sale_proposal_id       = $request->sale_id;
            $sale_payment->payment_method         = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $sale_payment->bank_holder_name = $request->bank_holder_name;
                $sale_payment->bank_account_no  = $request->bank_account_no;
            else:
                $sale_payment->bank_holder_name = null;
                $sale_payment->bank_account_no  = null; 
            endif;
            $sale_payment->amount          = $request->amount;
            $sale_payment->description     = $request->description;
            $sale_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $sale_payment->document_id = $this->upload->upload('proposal',$sale_payment->document_id,$request->document);
            endif;
            $sale_payment->save();
        
             //payment calculation 
            $branch           =  Branch::find($saleFind->branch_id);
            $branch->balance  = $branch->balance-$sale_payment->amount;
            $branch->save();
            //branch statements
            $branchStatement                  = BranchStatement::where('sale_proposal_payment_id',$request->payment_id)->first();
            $branchStatement->business_id     = business_id();
            $branchStatement->branch_id       = $saleFind->branch_id;
            $branchStatement->type            = StatementType::INCOME;
            $branchStatement->amount          = $request->amount; 
            $branchStatement->save(); 

            //customer balance 
            if($saleFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer = $this->customerRepo->getFind($saleFind->customer_id);
                $customer->balance = $customer->balance + $sale_payment->amount;
                $customer->save();
            endif; 

            //branch expense
            //to account 
            $account             = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance + $sale_payment->amount);
            $account->save();
            //end to account
 
            $bankTransaction->amount      = $sale_payment->amount; 
            $bankTransaction->save(); 
            //branch expense

            return true;
        } catch (\Throwable $th) {   
            DB::rollBack();
            return false;
        }
    }

    public function deletePayment($id){
        $salePayment      = SaleProposalPayment::find($id);
        $sale             = $this->getFind($salePayment->sale_proposal_id);

        $branch           =  Branch::find($sale->branch_id);
        $branch->balance  = $branch->balance + $salePayment->amount;
        $branch->save();

        //customer balance 
        if($sale->customer_type == CustomerType::EXISTING_CUSTOMER):
            $customer          = $this->customerRepo->getFind($sale->customer_id);
            $customer->balance = $customer->balance - $salePayment->amount;
            $customer->save();
        endif; 
        $this->upload->unlinkImage($salePayment->document_id);

        //branch expense
        $bankTransaction     = BankTransaction::where('sale_prop_pay_id',$salePayment->id)->first();
        if($bankTransaction):
        //to account 
        $account             = Account::find($bankTransaction->account_id);
        $account->balance    = ($account->balance - $salePayment->amount);
        $account->save();
        //end to account 
        $bankTransaction->delete(); 
        //branch expense
        endif;

        return SaleProposalPayment::destroy($id);
    }
}