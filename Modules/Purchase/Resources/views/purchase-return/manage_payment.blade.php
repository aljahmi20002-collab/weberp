<div class="row purchase_details_modal border-bottom py-2">
    <div class="col-lg-4">
        <b>{{ __('supplier') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>: {{ @$purchase_return->supplier->name }}
        </div>
        <div class="d-flex">
            <span>{{ __('company') }}</span>: {{ @$purchase_return->supplier->company_name }}
        </div> 
        <div class="d-flex">
            <span>{{ __('phone') }}</span>: {{ @$purchase_return->supplier->phone }}
        </div>
   
    </div>
    <div class="col-lg-4">
        <b>{{ __('business') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>:  {{ @$purchase_return->business->business_name }} 
        </div>
        <div >
            <span>{{ __('branch') }}</span>: 
            @foreach ($purchase_return->purchased_return_branch as $branch)
                <span class="badge badge-primary badge-pill">{{ @$branch->name }}</span>
            @endforeach 
        </div>
    </div>
    <div class="col-lg-4">  
        <div class="d-flex">
            <span>{{ __('return_no') }}</span>: {{ @$purchase_return->return_no }}
        </div>  
        <div  >
            <span>{{ __('total_return_amount') }}</span>: {{ businessCurrency($purchase_return->business_id) }} {{ number_format($purchase_return->total_purchase_return_price,2) }}
        </div>
        <div >
            <span>{{ __('payment_status') }}</span>: {!! $purchase_return->my_payment_status !!}
        </div>
        <div class="text-primary">
            <span><b>{{ __('total_amount') }}</span>: {{ businessCurrency($purchase_return->business_id) }} {{ number_format($purchase_return->dueAmount,2) }}</b>
        </div>
    </div>
</div>

<h5 class="mt-3">{{ __('payment_list') }}</h5>
<div class="col-lg-12">
    <div class="table-responsive table-responsive category-table product-view-table mt-2">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="border-bottom bg-primary text-white head align-middle">
                    <th>{{ __('date') }}</th>
                    <th>{{ __('purchase_no') }}</th>
                    <th>{{ __('payment_info') }}</th> 
                    <th>{{ __('amount') }}</th> 
                    <th>{{ __('document') }}</th> 
                    <th>{{ __('description') }}</th>  
                    @if(hasPermission('purchase_return_update_payment') || hasPermission('purchase_return_delete_payment')) 
                    <th>{{ __('action') }} </th> 
                    @endif
                </tr>
            </thead>
            <tbody > 
                @foreach ($purchase_return->payments as $payment)                    
                    <tr>
                        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y')}}</td>
                        <td data-title="{{ __('return_no') }}">{{  $payment->purchasereturn->return_no}}</td>
                        <td data-title="{{ __('payment_info') }}">
                            <div class="d-flex">
                                <b>{{ __('payment_method') }}</b>: {{ __(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method)) }}
                            </div>
                            @if($payment->payment_method == \Modules\Purchase\Enums\PaymentMethod::BANK)
                                <div class="d-flex">
                                   <b>{{ __('holder_name') }}</b>: {{ $payment->bank_holder_name }}
                                </div>
                                <div class="d-flex">
                                    <b>{{ __('account_no') }}</b>: {{ $payment->bank_account_no }}
                                </div>
                            @endif
                        </td> 
                        <td data-title="{{ __('amount') }}">{{ @businessCurrency($payment->purchasereturn->business_id) }} {{ @$payment->amount }}</td> 
                        <td data-title="{{ __('document') }}"><a href="{{ @$payment->documents }}">{{ __('download') }}</a></td> 
                        <td data-title="{{ __('description') }}">{{ $payment->description }}</td> 
                        @if(hasPermission('purchase_return_update_payment') || hasPermission('purchase_return_delete_payment')) 
                        <td data-title="{{ __('action') }}">
                            @if(hasPermission('purchase_return_update_payment')) 
                                <a href="#"  class="edit_payment px-3" data-url="{{ route('purchase.return.edit.payment',$payment->id) }}"><i class="fa fa-pen"></i></a>
                            @endif
                            @if(hasPermission('purchase_return_delete_payment')) 
                                <a href="{{ route('purchase.return.delete.payment',$payment->id) }}"  class="px-3"  ><i class="fa fa-trash"></i></a>
                            @endif
                        </td>
                        @endif 
                    </tr>
                @endforeach
            </tbody> 
        </table>
    </div> 
</div>
 
@if(hasPermission('purchase_return_add_payment'))
<div id="payment_form"> 
    <h5 class="mt-3">{{ __('add_payment') }}</h5>
    <form action="{{ route('purchase.return.add.payment',['purchase_return_id'=>$purchase_return->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">   
            <div class="col-lg-6 mt-2">
                <label  class="form-label">{{ __('payment_method') }} <span class="text-danger">*</span></label>
                <select class="form-control form--control select2 purchase_payment_method" name="payment_method" id="purchase_payment_method" required > 
                    @foreach (\Config::get('pos_default.purchase.payment_method') as $key=>$payment_method)
                        <option value="{{ $key }}" @if(old('purchase_status') == $key) selected @endif>{{@__($payment_method) }}</option>
                    @endforeach
                </select> 
            </div>  
            <div class="col-lg-6 mt-2 ">
                <label  class="form-label">{{ __('amount') }} <span class="text-danger">*</span> </label>
                <input type="text" name="amount" class="form-control form--control" value="{{ $purchase_return->dueAmount }}" required/>
            </div>  
            <div class="col-lg-6 mt-2 ">
                <label  class="form-label">{{ __('date') }} <span class="text-danger">*</span> </label>
                <input type="date" name="paid_date" class="form-control form--control" value="{{ date('Y-m-d') }}" required/>
            </div>  
            <div class="col-lg-6 mt-2 ">
                <label  class="form-label">{{ __('document') }}  </label>
                <input type="file" name="document" class="form-control form--control" />
            </div>  
            <div class="col-lg-6 mt-2 purchase_bank d-none">
                <label  class="form-label">{{ __('bank_holder_name') }} </label>
                <input type="text" name="bank_holder_name" class="form-control form--control" />
            </div>  
            <div class="col-lg-6 mt-2 purchase_bank d-none">
                <label  class="form-label">{{ __('bank_account_no') }}  </label>
                <input type="text" name="bank_account_no" class="form-control form--control" />
            </div>  
            <div class="col-lg-12 mt-2  ">
                <label  class="form-label">{{ __('description') }}  </label>
            <textarea name="description" class="form-control form--control"></textarea>
            </div>  
        </div>
        <div class="mt-3 text-end">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('payment') }}</button>
        </div>
    </form>
</div>
@endif
<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $('.purchase_payment_method').change(function(){
         if($(this).val() == 1){
             $('.purchase_bank').addClass('d-none');
        }else{
             $('.purchase_bank').removeClass('d-none');
        }
    });

    $('.edit_payment').click(function(){  
        $.ajax({
            url: $(this).data('url'),
            method: 'get',
            dataType: 'html', 
            success: (response) => { 
                $('#payment_form').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    });
</script>