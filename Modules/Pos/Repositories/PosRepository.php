<?php
namespace Modules\Pos\Repositories;

use App\Enums\StatementType;
use App\Repositories\Upload\UploadInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Pos\Entities\PosItem;
use Modules\Pos\Repositories\PosInterface;
use Modules\Product\Entities\VariationLocationDetails;
use Illuminate\Support\Str;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;
use Modules\Account\Enums\AccountType;
use Modules\Branch\Entities\Branch;
use Modules\Branch\Entities\BranchStatement;
use Modules\Customer\Enums\CustomerType;
use Modules\Customer\Repositories\CustomerInterface;
use Modules\Expense\Entities\Expense;
use Modules\Pos\Entities\Pos;
use Modules\Pos\Entities\PosPayment;
use Modules\Purchase\Enums\PaymentMethod;
use Modules\TaxRate\Entities\TaxRate;

class PosRepository implements PosInterface{

    protected $model,$upload,$customerRepo;
    public function __construct(Pos $model,UploadInterface $upload,CustomerInterface $customerRepo)
    {
        $this->model        = $model;
        $this->upload       = $upload;
        $this->customerRepo = $customerRepo;    
    }
    public function get(){
        return Pos::with('payments','posItems','TaxRate','customer','branch')->where(function($query){
            $query->where('business_id',business_id());
            if(isUser()): 
                $query->where('branch_id',Auth::user()->branch_id); 
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    public function getAllPos(){
        return Pos::with('payments', 'TaxRate','customer','branch','user')->where(function($query){
            $query->where('business_id',business_id());
            if(isUser()): 
                $query->where('branch_id',Auth::user()->branch_id); 
            endif;
        })->orderByDesc('id')->get();
    }

    public function getInvoice(){
        return Pos::with('payments','posItems','TaxRate','customer','branch')->where(function($query){
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
 
            $pos                  = new $this->model(); 
            $pos->business_id     = business_id();
            $pos->branch_id       = $branch_id;
            if($request->customer_id == 'walk_customer'):
                $pos->customer_type   = CustomerType::WALK_CUSTOMER;
                if(!empty($request->customer_phone)):
                    $pos->customer_phone   = $request->customer_phone;
                endif;
            else: 
                $pos->customer_type   = CustomerType::EXISTING_CUSTOMER;
                $pos->customer_id     = $request->customer_id; 
            endif;

            $pos->invoice_no      = $this->invoiceNo();
            if(!blank($request->discount_amount)):
                $pos->discount_amount = $request->discount_amount;
            endif;
            $pos->order_tax_id    = $request->tax_id;
            $pos->shipping_details= $request->shipping_details;
            $pos->shipping_address= $request->shipping_address;
            if(!blank($request->shipping_charge)):
                $pos->shipping_charge = $request->shipping_charge;
            endif;
            $pos->shipping_status = $request->shipping_status;

            $taxRate                 = TaxRate::find($request->tax_id); 
            $pos->order_tax_percent = @$taxRate->tax_rate; 
            $pos->total_price       = $request->total_price;
            $pos->order_tax_amount  = $request->total_tax_amount;
            $pos->total_sell_price  = $request->total_sell_price;


            $pos->created_by      = Auth::user()->id;
            $pos->save();
            if($pos): 
                $request['pos_id'] = $pos->id; 
                $this->posItem($request); 

                //payment
                $findPos                   = $this->getFind($pos->id);
                $request['amount']         = $findPos->DueAmount;
                $request['payment_method'] = PaymentMethod::CASH;
                $request['paid_date']      = Carbon::today()->format('Y-m-d'); 

                //customer balance 
                if($findPos->customer_type == CustomerType::EXISTING_CUSTOMER):
                    $customer          = $this->customerRepo->getFind($findPos->customer_id);
                    $customer->balance = $customer->balance - $request['amount'];
                    $customer->save();
                endif; 
                $this->addPayment($request); 

                //end payment 
                return $this->getFind($pos->id);
            endif;
            return false; 
        } catch (\Throwable $th) {  
           
            return false;
        }
    }

    public function posItem($request){ 
        foreach ($request->variation_locations as $variationLocation) {            
            $variationLocationDetails  = VariationLocationDetails::find($variationLocation['id']);
            $posItem                   = new PosItem();
            $posItem->pos_id           = $request->pos_id;
            $posItem->business_id      = business_id();
            $posItem->branch_id        = $variationLocationDetails->branch_id;
            $posItem->vari_loc_det_id  = $variationLocationDetails->id;
            $posItem->sale_quantity    = $variationLocation['quantity'];
            $posItem->unit_price       =  $variationLocation['unit_price'];
            $posItem->total_unit_price = ($variationLocation['unit_price'] * $variationLocation['quantity']); 
            $posItem->save();

            $variationLocationDetails->qty_available   = ($variationLocationDetails->qty_available - $variationLocation['quantity']);
            $variationLocationDetails->save();
        }
    }

    public function invoiceNo(){
        return date('dmyhis').Str::random(3);
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

    public function update($id,$request){
        try {
            if(business()):
                $branch_id   = $request->branch_id;
            else:
                $branch_id   = Auth::user()->branch_id;
            endif; 
 
            $pos                  =  $this->getFind($id);  

            //customer balanace
            if($pos->customer_type == CustomerType::EXISTING_CUSTOMER && $request->customer_id == 'walk_customer'):
                $customer          = $this->customerRepo->getFind($pos->customer_id);
                $customer->balance = $customer->balance + $pos->payments->sum('amount');
                $customer->save(); 
          
            endif;
 
            $pos->business_id     = business_id();
            $pos->branch_id       = $branch_id;
            if($request->customer_id == 'walk_customer'):
                $pos->customer_type   = CustomerType::WALK_CUSTOMER;
                $pos->customer_id     = null; 

                if(!empty($request->customer_phone)):
                    $pos->customer_phone   = $request->customer_phone;
                endif;
                
            else: 
                $pos->customer_type   = CustomerType::EXISTING_CUSTOMER;
                $pos->customer_id     = $request->customer_id; 
            endif;

            $pos->invoice_no      = $this->invoiceNo();
            if(!blank($request->discount_amount)):
                $pos->discount_amount = $request->discount_amount;
            endif;
            $pos->order_tax_id    = $request->tax_id;
            $pos->shipping_details= $request->shipping_details;
            $pos->shipping_address= $request->shipping_address;
            if(!blank($request->shipping_charge)):
                $pos->shipping_charge = $request->shipping_charge;
            endif;
            $pos->shipping_status = $request->shipping_status;

            $taxRate                 = TaxRate::find($request->tax_id); 
            $pos->order_tax_percent = @$taxRate->tax_rate; 
            $pos->total_price       = $request->total_price;
            $pos->order_tax_amount  = $request->total_tax_amount;
            $pos->total_sell_price  = $request->total_sell_price;
            
            $pos->created_by      = Auth::user()->id;
            $pos->save();
            if($pos):

                $request['pos_id']    = $pos->id;
                $request['branch_id'] = $branch_id;

                $this->posSaleReverse($request); 
                $this->posItem($request); 
 
                //payment
                $findPos                   = $this->getFind($pos->id);
              
                $branch           = Branch::find($findPos->branch_id);    
                $branch->balance  = $branch->balance+$findPos->payments->sum('amount');
                $branch->save(); 

                PosPayment::where('pos_id', $pos->id)->delete();
                $DueAmounts                = $this->getFind($pos->id)->DueAmount;
                $request['amount']         = $DueAmounts;
                $request['payment_method'] = PaymentMethod::CASH;
                $request['paid_date']      = Carbon::today()->format('Y-m-d'); 
                
                // customer balance 
                if($pos->customer_type == CustomerType::EXISTING_CUSTOMER && $request->customer_id != 'walk_customer'):
                    $customer          = $this->customerRepo->getFind($findPos->customer_id);
                    $customer->balance = $customer->balance - $request['amount'];
                    $customer->save();
                endif; 
                $this->addPayment($request);  
                //end payment  
                return true;
            endif;
            return false; 
        } catch (\Throwable $th) { 
            return false;
        }
    }
 
 
    public function posSaleReverse($request){
        $pos           = $this->getFind($request->pos_id);
        foreach ($pos->posItems as $item) {            
            $variationLocationDetails                  = VariationLocationDetails::find($item->vari_loc_det_id);
            $variationLocationDetails->qty_available   = ($variationLocationDetails->qty_available + $item->sale_quantity);
            $variationLocationDetails->save(); 
            PosItem::destroy($item->id);
        } 

    }
    public function delete($id){
        try {
            $pos   = $this->getFind($id);
            foreach ($pos->posItems as $item) {            
                $variationLocationDetails                  = VariationLocationDetails::find($item->vari_loc_det_id);
                $variationLocationDetails->qty_available   = ($variationLocationDetails->qty_available + $item->sale_quantity);
                $variationLocationDetails->save(); 
                PosItem::destroy($item->id);
            }  
            $branch           =  Branch::find($pos->branch_id);
            $branch->balance  = $branch->balance + $pos->payments->sum('amount');
            $branch->save();

            if($pos->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer          = $this->customerRepo->getFind($pos->customer_id);
                $customer->balance = ($customer->balance + $pos->TotalSalePrice) - $pos->payments->sum('amount');
                $customer->save();
            endif;   
            foreach ($pos->payments as $payment) {
                foreach ($payment->bankTransactions as $transaction) {
                     $account              = Account::find($transaction->account_id); 
                     $account->balance     = ($account->balance - $transaction->amount);
                     $account->save();
                }
            }

            return Pos::destroy($pos->id);
        } catch (\Throwable $th) { 
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



    public function VariationLocationSearchFind($request){
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
    

    
    public function variationLocationItemFind($id){
        return VariationLocationDetails::with('product','ProductVariation')->find($id);
    }
    
    public function variationLocationItemFindGet($id){
        return VariationLocationDetails::with('product','ProductVariation')->find($id);
    }
  
    //payment
    public function addPayment($request){
       
        try {

            $pos_payment                         = new PosPayment();
            $pos_payment->pos_id                 = $request->pos_id;
            $pos_payment->payment_method         = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $pos_payment->bank_holder_name = $request->bank_holder_name;
                $pos_payment->bank_account_no  = $request->bank_account_no;
            else:
                $pos_payment->bank_holder_name = null;
                $pos_payment->bank_account_no  = null;
            endif;
            $pos_payment->amount          = $request->amount;
            $pos_payment->description     = $request->description;
            $pos_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $pos_payment->document_id = $this->upload->upload('pos','',$request->document);
            endif;
            $pos_payment->save();

            //payment calculation
            $posFind          = $this->getFind($request->pos_id);
            $branch           = Branch::find($posFind->branch_id);    
            $branch->balance  = $branch->balance-$pos_payment->amount;
            $branch->save();

            //branch statements
            $branchStatement                 = new BranchStatement();
            $branchStatement->pos_id         = $request->pos_id;
            $branchStatement->pos_payment_id = $pos_payment->id;
            $branchStatement->business_id    = business_id();
            $branchStatement->branch_id      = $posFind->branch_id;
            $branchStatement->type           = StatementType::INCOME;
            $branchStatement->amount         = $pos_payment->amount;
            $branchStatement->note           = 'Pos Sale Income';
            $branchStatement->save(); 
 
            //customer balance 
            if($posFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer          = $this->customerRepo->getFind($posFind->customer_id);
                $customer->balance = $customer->balance + $pos_payment->amount;
                $customer->save();
            endif; 
 
            //branch expense
            //to account 
            $account             = Account::find(saleDefaultAccount($posFind->branch_id));
            $account->balance    = ($account->balance + $pos_payment->amount);
            $account->save();
            //end to account

            $bankTransaction              = new BankTransaction();
            $bankTransaction->user_type   = AccountType::BRANCH;
            $bankTransaction->business_id = business_id();   
            $bankTransaction->branch_id   = $posFind->branch_id;   
            $bankTransaction->pos_pay_id = $pos_payment->id;
            $bankTransaction->account_id  = $account->id;
            $bankTransaction->type        = StatementType::EXPENSE;
            $bankTransaction->amount      = $pos_payment->amount;
            $bankTransaction->note        = 'product_pos_payment';
            $bankTransaction->save(); 
            //branch expense
    
            return true;
        } catch (\Throwable $th) {  
            DB::rollBack();
            return false;
        }
    }

    public function getFindPayment($id){
        return PosPayment::find($id);
    }
    public function updatePayment($request){
        try {
           
            $pos_payment      = PosPayment::find($request->payment_id);

            $bankTransaction          = BankTransaction::where('pos_pay_id',$pos_payment->id)->first();
            //to account 
            $back_account             = Account::find($bankTransaction->account_id);
            $back_account->balance    = ($back_account->balance - $pos_payment->amount);
            $back_account->save();
            //end to account

            $posFind          = $this->getFind($request->pos_id); 
            $branch           = Branch::find($posFind->branch_id);    
            $branch->balance  = $branch->balance + $pos_payment->amount;
            $branch->save();

            //customer balance 
            if($posFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer          = $this->customerRepo->getFind($posFind->customer_id);
                $customer->balance = $customer->balance - $pos_payment->amount;
                $customer->save();
            endif;   

            $pos_payment->pos_id                 = $request->pos_id;
            $pos_payment->payment_method         = $request->payment_method; 
            if($request->payment_method == PaymentMethod::BANK): 
                $pos_payment->bank_holder_name = $request->bank_holder_name;
                $pos_payment->bank_account_no  = $request->bank_account_no;
            else:
                $pos_payment->bank_holder_name = null;
                $pos_payment->bank_account_no  = null; 
            endif;
            $pos_payment->amount          = $request->amount;
            $pos_payment->description     = $request->description;
            $pos_payment->paid_date       = $request->paid_date;
            if($request->document && !blank($request->document)):
                $pos_payment->document_id = $this->upload->upload('pos',$pos_payment->document_id,$request->document);
            endif;
            $pos_payment->save();

            //payment calculation

            $branch           = Branch::find($posFind->branch_id);    
            $branch->balance  = $branch->balance-$pos_payment->amount;
            $branch->save();
         
            //branch statements
            $branchStatement                 = BranchStatement::where('pos_payment_id',$pos_payment->id)->first(); 
            $branchStatement->business_id    = business_id();
            $branchStatement->branch_id      = $posFind->branch_id;
            $branchStatement->type           = StatementType::INCOME;
            $branchStatement->amount         = $pos_payment->amount;
            $branchStatement->note           = 'Pos Sale Income';
            $branchStatement->save(); 

            //customer balance 
            if($posFind->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer          = $this->customerRepo->getFind($posFind->customer_id);
                $customer->balance = $customer->balance + $pos_payment->amount;
                $customer->save();
            endif; 

                      
            //branch expense
            //to account 
            $account             = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance + $pos_payment->amount);
            $account->save();
            //end to account
 
            $bankTransaction->amount      = $pos_payment->amount; 
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
            $posPayment       = PosPayment::find($id);
            $pos              = $this->getFind($posPayment->pos_id);
            $branch           =  Branch::find($pos->branch_id);
            $branch->balance  = $branch->balance + $posPayment->amount;
            $branch->save();
     
            //customer balance 
            if($pos->customer_type == CustomerType::EXISTING_CUSTOMER):
                $customer          = $this->customerRepo->getFind($pos->customer_id);
                $customer->balance = $customer->balance - $posPayment->amount;
                $customer->save();
            endif; 
    
            $this->upload->unlinkImage($posPayment->document_id); 
    
            //branch expense
            $bankTransaction     = BankTransaction::where('pos_pay_id',$posPayment->id)->first();
            //to account 
            $account             = Account::find($bankTransaction->account_id);
            $account->balance    = ($account->balance - $posPayment->amount);
            $account->save();
            //end to account 
            $bankTransaction->delete(); 
            //branch expense
    
            return PosPayment::destroy($id);
        } catch (\Throwable $th) {
             return false;
        }
    } 



    //business dashboard
    public function ThirtyDaysPosChart(){
        $data =[];
        $start_date = Carbon::today()->subDays(30)->startOfDay()->toDateTimeString();
        $end_date   = Carbon::today()->endOfDay()->toDateTimeString();
        $data['dates']                 = []; 
        $data['pos_amount']          = []; 
        $data['pos_payment']         = [];  
        for ($i=0; $i <30 ; $i++) { 
            $d                  = Carbon::parse($start_date)->addDay($i)->format('d-m-Y');  
            $data['dates'][]    = $d;
            $start_d            = Carbon::parse($start_date)->addDay($i)->startOfDay()->toDateTimeString();
            $end_d              = Carbon::parse($start_date)->addDay($i)->endOfDay()->toDateTimeString();
            $poses              = Pos::with('posItems','payments','business','TaxRate')->where('business_id',business_id())->whereBetween('updated_at',[$start_d,$end_d])->get(); 
            $totalAmount        = 0;
            $totalPayment       = 0;
            foreach ($poses as $pos) {
                $totalAmount  += $pos->TotalSalePrice;
                $totalPayment += $pos->payments->sum('amount');
            }
            $data['pos_amount'][$d]         = $totalAmount; 
            $data['pos_payment'][$d]        = $totalPayment; 
        } 
        return $data;
    }
    
    //branch dashboard
    public function branchThirtyDaysPosChart(){
        $data =[];
        $start_date = Carbon::today()->subDays(30)->startOfDay()->toDateTimeString();
        // $end_date   = Carbon::today()->endOfDay()->toDateTimeString();
        $data['dates']                 = []; 
        $data['pos_amount']          = []; 
        $data['pos_payment']         = [];  
        for ($i=0; $i <30 ; $i++) { 
            $d                  = Carbon::parse($start_date)->addDay($i)->format('d-m-Y');  
            $data['dates'][]    = $d;
            $start_d            = Carbon::parse($start_date)->addDay($i)->startOfDay()->toDateTimeString();
            $end_d              = Carbon::parse($start_date)->addDay($i)->endOfDay()->toDateTimeString();
            $poses              = Pos::with('posItems','payments','business','TaxRate')->where('business_id',business_id())->where('branch_id',Auth::user()->branch_id)->whereBetween('updated_at',[$start_d,$end_d])->get(); 
            $totalAmount        = 0;
            $totalPayment       = 0;
            foreach ($poses as $pos) {
                $totalAmount  += $pos->TotalSalePrice;
                $totalPayment += $pos->payments->sum('amount');
            }
            $data['pos_amount'][$d]         = $totalAmount; 
            $data['pos_payment'][$d]        = $totalPayment; 
        } 
        return $data;
    }
 

    public function getCustomerPosSales($customer_id)
    {
        return $this->model::where(function ($query) use ($customer_id) {
            $query->where('business_id', business_id());
            $query->where('customer_id', $customer_id);
            if (isUser()) :
                $query->where('branch_id', Auth::user()->branch_id);
            endif;
        })->orderByDesc('id')->get();
    }
    

    public function getCustomerPosSalesPayments($customer_id)
    {
        return PosPayment::where(function ($query) use ($customer_id) {
            $query->whereHas('pos', function ($query) use ($customer_id) {
                $query->where('business_id', business_id());
                $query->where('customer_id', $customer_id);
                if (isUser()) :
                    $query->where('branch_id', Auth::user()->branch_id);
                endif;
            });
        })->orderByDesc('id')->get();
    }


}