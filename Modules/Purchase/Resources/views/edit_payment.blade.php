
<h5 class="mt-3">{{ __('edit_payment') }}</h5>
<form action="{{ route('purchase.update.payment',['payment_id'=>$payment->id,'purchase_id'=>$payment->purchase_id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">   
        <div class="col-lg-6 mt-2">
            <label  class="form-label">{{ __('payment_method') }} <span class="text-danger">*</span></label>
            <select class="form-control form--control select2 purchase_payment_method" name="payment_method" id="purchase_payment_method" required > 
                @foreach (\Config::get('pos_default.purchase.payment_method') as $key=>$payment_method)
                    <option value="{{ $key }}" @if(old('purchase_status',$payment->payment_method) == $key) selected @endif>{{@__($payment_method) }}</option>
                @endforeach
            </select> 
        </div>  
        <div class="col-lg-6 mt-2 ">
            <label  class="form-label">{{ __('amount') }} <span class="text-danger">*</span> </label>
            <input type="text" name="amount" class="form-control form--control" value="{{ $payment->amount }}" required/>
        </div>  
        <div class="col-lg-6 mt-2 ">
            <label  class="form-label">{{ __('date') }} <span class="text-danger">*</span> </label>
            <input type="date" name="paid_date" class="form-control form--control" value="{{ $payment->paid_date }}" required/>
        </div>  
        <div class="col-lg-6 mt-2 ">
            <label  class="form-label">{{ __('document') }}  </label>
            <input type="file" name="document" class="form-control form--control" />
        </div>  
        <div class="col-lg-6 mt-2 purchase_bank @if($payment->payment_method != \Modules\Purchase\Enums\PaymentMethod::BANK) d-none @endif">
            <label  class="form-label">{{ __('bank_holder_name') }} </label>
            <input type="text" name="bank_holder_name" class="form-control form--control" value="{{ $payment->bank_holder_name }}" />
        </div>  
        <div class="col-lg-6 mt-2 purchase_bank @if($payment->payment_method != \Modules\Purchase\Enums\PaymentMethod::BANK) d-none @endif">
            <label  class="form-label">{{ __('bank_account_no') }}  </label>
            <input type="text" name="bank_account_no" class="form-control form--control" value="{{ $payment->bank_account_no }}" />
        </div>  
        <div class="col-lg-12 mt-2  ">
            <label  class="form-label">{{ __('description') }}  </label>
           <textarea name="description" class="form-control form--control">{{ $payment->description }}</textarea>
        </div>  
    </div>
    <div class="mt-3 text-end">
        <button type="submit" class="btn btn-sm btn-primary">{{ __('update_payment') }}</button>
    </div>
</form>
 
<script type="text/javascript">
    $('.purchase_payment_method').change(function(){
         if($(this).val() == 1){
             $('.purchase_bank').addClass('d-none');
        }else{
             $('.purchase_bank').removeClass('d-none');
        }
    });

 
</script>