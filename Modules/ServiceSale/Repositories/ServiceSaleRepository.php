<?php
namespace Modules\ServiceSale\Repositories;

use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Enums\CustomerType;
use Modules\Service\Entities\Service;
use Modules\ServiceSale\Entities\ServiceSale;
use Illuminate\Support\Str;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchStatement;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Purchase\Enums\PaymentMethod;
use Modules\ServiceSale\Entities\ServiceSaleItem;
use Modules\ServiceSale\Entities\ServiceSalePayment;
use Modules\TaxRate\Entities\TaxRate;

class ServiceSaleRepository implements ServiceSaleInterface{

    protected $uploadRepo,$customerRepo;
    public function __construct(UploadInterface $uploadRepo,CustomerInterface $customerRepo)
    {
        $this->uploadRepo   = $uploadRepo;
        $this->customerRepo = $customerRepo;
    }
    public function get(){
        return ServiceSale::with('payments', 'TaxRate','customer','branch','user')->where(function($query){
                $query->where('business_id',business_id());
                if(isUser()):
                    $query->where('branch_id',Auth::user()->branch_id); 
                endif;
        })->orderByDesc('id')->get();
    }
    
    
    public function getFind($id){
        return ServiceSale::find($id);
    }

    public function invoiceNo(){
        return date('dmyhis').Str::random(3);
    }
    public function store($request){
 
            try {
                if(business()):
                    $branch_id   = $request->branch_id;
                else:
                    $branch_id   = Auth::user()->branch_id;
                endif;  
                $sale                  = new ServiceSale(); 
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

                
                $taxRate                 = TaxRate::find($request->tax_id); 
                $sale->order_tax_percent = @$taxRate->tax_rate; 
                $sale->total_price       = $request->total_price;
                $sale->order_tax_amount  = $request->total_tax_amount;
                $sale->total_sell_price  = $request->total_sell_price;


                $sale->created_by      = Auth::user()->id;
                $sale->save();
                if($sale): 
                    $request['sale_id']   = $sale->id;
                    $request['branch_id'] = $branch_id;
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
      
        foreach ($request->service_items as $serviceItem) {            
            $service                    =  Service::find($serviceItem['id']);
            $saleItem                   =  new ServiceSaleItem();
            $saleItem->service_sale_id  =  $request->sale_id;
            $saleItem->business_id      =  business_id();
            $saleItem->branch_id        =  $request->branch_id;
            $saleItem->service_id       =  $service->id;
            $saleItem->sale_quantity    =  $serviceItem['quantity'];
            $saleItem->unit_price       =  $serviceItem['unit_price'];
            $saleItem->total_unit_price = ($serviceItem['unit_price'] * $serviceItem['quantity']); 
            $saleItem->save(); 
        }
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
                $customer            = Customer::find($sale->customer_id);
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
            
            $taxRate                 = TaxRate::find($request->tax_id); 
            $sale->order_tax_percent = @$taxRate->tax_rate; 
            $sale->total_price       = $request->total_price;
            $sale->order_tax_amount  = $request->total_tax_amount;
            $sale->total_sell_price  = $request->total_sell_price;

            $sale->created_by      = Auth::user()->id;
            $sale->save();
            if($sale):  

                $request['sale_id']   = $sale->id;
                $request['branch_id'] = $branch_id;
                ServiceSaleItem::where('service_sale_id',$sale->id)->delete();
                $this->saleItem($request);

                //customer balance 
                if($sale->customer_type == CustomerType::EXISTING_CUSTOMER):
                    $findSales           = $this->getFind($sale->id);
                    $customer            = Customer::find($sale->customer_id);
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
 
        $branch           =  Branch::find($sale->branch_id);
        $branch->balance  = $branch->balance + $sale->payments->sum('amount');
        $branch->save();

        foreach ($sale->payments as $payment) {
            foreach ($payment->bankTransactions as $transaction) {
                 $account              = Account::find($transaction->account_id); 
                 $account->balance     = ($account->balance - $transaction->amount);
                 $account->save();
            }
         }
         
        return ServiceSale::destroy($id);
    }

    public function serviceItemFind($request){
        return Service::where('business_id',business_id())->where('name',$request->search)->first();
    }
    public function serviceItemsFind($request){ 
        return Service::where('business_id',business_id())->where('name','like','%'.$request->search.'%')->limit(30)->get();
    }
    public function serviceItem($request){
        return Service::find($request->service_id);
    }
 
    //payment
    public function addPayment($request){
       
        try {
            $sale_payment                         = new ServiceSalePayment();
            $sale_payment->service_sale_id        = $request->service_sale_id;
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
                $sale_payment->document_id = $this->uploadRepo->upload('servicesale','',$request->document);
            endif;
            $sale_payment->save();

            //payment calculation
            $saleFind         = $this->getFind($request->service_sale_id); 
            $branch           =  Branch::find($saleFind->branch_id);
            $branch->balance  = $branch->balance-$sale_payment->amount;
            $branch->save();
 
            //branch statements
            $branchStatement                          = new BranchStatement();
            $branchStatement->service_sale_id         = $request->service_sale_id; 
            $branchStatement->service_sale_payment_id = $sale_payment->id; 
            $branchStatement->business_id             = business_id();
            $branchStatement->branch_id               = $saleFind->branch_id;
            $branchStatement->type                    = StatementType::INCOME;
            $branchStatement->amount                  = $sale_payment->amount;
            $branchStatement->note                    = 'Service Sale Income';
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
            $bankTransaction->ser_sale_pay_id = $sale_payment->id;
            $bankTransaction->account_id  = $account->id;
            $bankTransaction->type        = StatementType::EXPENSE;
            $bankTransaction->amount      = $sale_payment->amount;
            $bankTransaction->note        = 'service_sale_payment';
            $bankTransaction->save(); 
            //branch expense
            
            return true;

        } catch (\Throwable $th) { 
            DB::rollBack();
            return false;
        }
    }

    public function getFindPayment($id){
        return ServiceSalePayment::find($id);
    }
    public function updatePayment($request){
        try { 
            $sale_payment        = ServiceSalePayment::find($request->payment_id);

            $bankTransaction     = BankTransaction::where('ser_sale_pay_id',$sale_payment->id)->first();
            //to account 
            $back_account             = Account::find($bankTransaction->account_id);
            $back_account->balance    = ($back_account->balance - $sale_payment->amount);
            $back_account->save();
            //end to account

            $saleFind         = $this->getFind($request->service_sale_id);  
            $branch           =  Branch::find($saleFind->branch_id);
            $branch->balance  = $branch->balance + $sale_payment->amount;
            $branch->save();

            //customer balance 
            if($saleFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer = $this->customerRepo->getFind($saleFind->customer_id);
                $customer->balance = $customer->balance - $sale_payment->amount;
                $customer->save();
            endif; 

            $sale_payment->service_sale_id        = $request->service_sale_id;
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
                $sale_payment->document_id = $this->uploadRepo->upload('servicesale',$sale_payment->document_id,$request->document);
            endif;
            $sale_payment->save();
        
             //payment calculation 
            $branch           =  Branch::find($saleFind->branch_id);
            $branch->balance  = $branch->balance-$sale_payment->amount;
            $branch->save();
            //branch statements
            $branchStatement                  = BranchStatement::where('service_sale_payment_id',$request->payment_id)->first();
            $branchStatement->service_sale_id = $request->service_sale_id;  
            $branchStatement->business_id     = business_id();
            $branchStatement->branch_id       = $saleFind->branch_id;
            $branchStatement->type            = StatementType::INCOME;
            $branchStatement->amount          = $request->amount;
            $branchStatement->note            = 'Service Sale Income';
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
        try {            
            $salePayment      = ServiceSalePayment::find($id);
            $sale             = $this->getFind($salePayment->service_sale_id);
    
            $branch           =  Branch::find($sale->branch_id);
            $branch->balance  = $branch->balance + $salePayment->amount;
            $branch->save();
    
            //customer balance 
            if($sale->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer          = $this->customerRepo->getFind($sale->customer_id);
                $customer->balance = $customer->balance - $salePayment->amount;
                $customer->save();
            endif; 
            $this->uploadRepo->unlinkImage($salePayment->document_id);
    
            //branch expense
            $bankTransaction     = BankTransaction::where('ser_sale_pay_id',$salePayment->id)->first();
            //to account 
            $account             = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance - $salePayment->amount);
            $account->save();
            //end to account 
            $bankTransaction->delete(); 
            //branch expense
            return ServiceSalePayment::destroy($id);
            
        } catch (\Throwable $th) {
            return false;
        }
    }



    //reports
    public function getReport($request){
      
        $date = explode(' - ', $request->date);
        if(is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString(); 
        }   
        
        $sales = ServiceSale::where(function($query)use($request){
                            $query->where('business_id',business_id());
                            if(isUser()):
                                $query->where('branch_id',Auth::user()->branch_id);
                            else:
                                if($request->branch_id):
                                    $query->where('branch_id',$request->branch_id);
                                endif;
                            endif;
                        })->whereBetween('updated_at',[$from,$to])->orderByDesc('id')->paginate(10);
        return $sales;
    }

    //get customer invoice 
    public function getCustomerInvoice($customer_id){
        
           return ServiceSale::where(function($query)use($customer_id){
                   $query->where('business_id',business_id()); 
                   $query->where('customer_id',$customer_id);
                   if(isUser()):
                       $query->where('branch_id',Auth::user()->branch_id);
                   endif;
           })->orderByDesc('id')->get();
       

    }


    public function getCustomerInvoicePayments($customer_id){
        return ServiceSalePayment::where(function($query)use($customer_id){
            $query->whereHas('sale',function($query)use($customer_id){
                $query->where('business_id',business_id());
                $query->where('customer_id',$customer_id);
                if(isUser()):
                    $query->where('branch_id',Auth::user()->branch_id);
                endif;
            });
        })->orderByDesc('id')->get();
    }

}