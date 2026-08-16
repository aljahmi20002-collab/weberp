<div class="row purchase_details_modal border-bottom py-2">
    <div class="col-lg-4">
        <b>{{ __('customer') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('type') }}</span>: {{ __(\Config::get('pos_default.customer_type.'.$sale->customer_type)) }}
        </div> 
        @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
            <div class="d-flex">
                <span>{{ __('name') }}</span>: {{ @$sale->customer->name }}
            </div> 
            <div class="d-flex">
                <span>{{ __('email') }}</span>: {{ @$sale->customer->email }}
            </div>
            <div class="d-flex">
                <span>{{ __('phone') }}</span>: {{ @$sale->customer->phone }}
            </div>
            <div class="d-flex">
                <span>{{ __('address') }}</span>: <span class="purchase_address"> {{ @$sale->customer->address }}</span>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <b>{{ __('business') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>:  {{ @$sale->business->business_name }} 
        </div>
        <div class="d-flex" >
            <span>{{ __('branch') }}</span>:  {{ @$sale->branch->name }}
        </div>
    </div>
 
    <div class="col-lg-4">   
        <div class="d-flex">
            <span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y h:i:s A') }}
        </div>
        <div class="d-flex">
            <span>{{ __('invoice_no') }}</span>: {{ @$sale->invoice_no }}
        </div>
        <div class="d-flex">
            <span>{{ __('order_tax') }}</span>: {{ @$sale->TaxRate->name }}
        </div> 
        <div  >
            <span>{{ __('total_sale_amount') }}</span>: {{ businessCurrency($sale->business_id) }} {{ number_format($sale->total_sale_price,2) }}
        </div>
        <div >
            <span>{{ __('payment_status') }}</span>:
            {!! @$sale->my_payment_status !!}
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
                    <th>{{ __('sale_invoice_no') }}</th>
                    <th>{{ __('payment_info') }}</th> 
                    <th>{{ __('amount') }}</th> 
                    <th>{{ __('document') }}</th> 
                    <th>{{ __('description') }}</th>  
                    @if(hasPermission('sale_proposal_update_payment') || hasPermission('sale_proposal_delete_payment')) 
                    <th>{{ __('action') }} </th> 
                    @endif
                </tr>
            </thead>
            <tbody > 
                @foreach ($sale->payments as $payment)                    
                    <tr>
                        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y')}}</td>
                        <td data-title="{{ __('return_no') }}">{{  $payment->sale->invoice_no}}</td>
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
                        <td data-title="{{ __('amount') }}">{{ @businessCurrency($payment->sale->business_id) }} {{ @$payment->amount }}</td> 
                        <td data-title="{{ __('document') }}"><a href="{{ @$payment->documents }}">{{ __('download') }}</a></td> 
                        <td data-title="{{ __('description') }}">{{ $payment->description }}</td> 
                        @if(hasPermission('sale_proposal_update_payment') || hasPermission('sale_proposal_delete_payment')) 
                        <td data-title="{{ __('action') }}">
                            @if(hasPermission('sale_proposal_update_payment')) 
                                <a href="#"  class="edit_payment px-3" data-url="{{ route('saleproposal.edit.payment',$payment->id) }}"><i class="fa fa-pen"></i></a>
                            @endif
                            @if(hasPermission('sale_proposal_delete_payment')) 
                                <a href="{{ route('saleproposal.delete.payment',$payment->id) }}"  class="px-3"  ><i class="fa fa-trash"></i></a>
                            @endif
                        </td>
                        @endif 
                    </tr>
                @endforeach
            </tbody> 
        </table>
    </div> 
</div>
@if(hasPermission('sale_add_payment'))
<div id="payment_form"> 
    <h5 class="mt-3">{{ __('add_payment') }}</h5>
    <form action="{{ route('saleproposal.add.payment',['sale_id'=>$sale->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">   
            <div class="col-lg-6 mt-2">
                <label  class="form-label">{{ __('payment_method') }} <span class="text-danger">*</span></label>
                <select class="form-control form--control select2 purchase_payment_method" name="payment_method" id="purchase_payment_method" required > 
                    @foreach (\Config::get('pos_default.purchase.payment_method') as $key=>$payment_method)
                        <option value="{{ $key }}" @if(old('payment_method') == $key) selected @endif>{{@__($payment_method) }}</option>
                    @endforeach
                </select> 
            </div>  
            <div class="col-lg-6 mt-2 ">
                <label  class="form-label">{{ __('amount') }} <span class="text-danger">*</span> </label>
                <input type="text" name="amount" class="form-control form--control" value="{{ $sale->dueAmount }}" required/>
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