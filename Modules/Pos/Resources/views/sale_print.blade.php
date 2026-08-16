 
<div id="sale_print"> 
    <div class="row"> 
        <div class="col-12 mt-5"> 
            <h3 class="text-center"><b class="cmr-5">{{ @$pos->business->business_name }}</b> </h3>
            <p class="text-center mt-2"> {{ @$pos->branch->name }}, {{ @$pos->branch->state }} </p> 
            <h4 class="text-center  ">Invoice</h4> 
            <div class="text-center"><small>( {{ __('pos') }} )</small></div>
            <div  class="text-right">
                <div class="d-inline-block"><span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse(@$pos->created_at)->format('d-m-Y h:i') }}</div>
            </div>
            <div class="mb-2 align-items-center print-info"> 
                <p>
                    {{ __('invoice_no') }}</span>: {{ @$pos->invoice_no }}<br/> 
                    <span>{{ __('shipping_status') }}</span>:   {{ __(\Config::get('pos_default.shpping_status.'.@$pos->shipping_status)) }}
                </p> 
                 <p ><b>Customer:</b></p> 
                 <p>
                    @if($pos->customer_type == \Modules\Customer\Enums\CustomerType::WALK_CUSTOMER)
                        {{ __(\Config::get('pos_default.customer_type.'.@$pos->customer_type))}}<br/>
                        {{ $pos->customer_phone}}<br/>
                    @else
                        {{ $pos->customer->name }}<br/>
                        {{ $pos->customer->phone }}<br/>
                        {{ $pos->customer->email }}<br/>
                        {{ $pos->customer->address }}
                    @endif
                 </p>
            </div>
        </div> 
    </div> 
 
   <!-- Responsive Dashboard Table -->
    <div class="mt-2">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="border-bottom"> 
                    <th>{{ __('product') }}</th>
                    <th>{{ __('quantity') }}</th>
                    <th>{{ __('unit_price') }}</th>  
                    <th>{{ __('total_unit_price') }}</th>   
                </tr>
            </thead>
            <tbody> 
                @php
                    $i=0;
                @endphp
                @foreach ($pos->posItems as $item) 
                    <tr>  
                        <td > 
                           <small> {{ @$item->variation_location->product->name }} - {{ @$item->variation_location->variation->name }} - {{ @$item->variation_location->ProductVariation->name }}</small>
                        </td> 
                        <td > 
                           <small> {{ @$item->sale_quantity }}</small>
                        </td> 
                        <td > 
                           <small>{{ businessCurrency($pos->business_id) }} {{ @$item->unit_price }}</small>
                        </td> 
                        <td > 
                           <small>{{ businessCurrency($pos->business_id) }}  {{ @$item->total_unit_price  }}</small>
                        </td> 
                    </tr>
                @endforeach  
            </tbody>
        </table>
    </div>

    <div class="row mt-3">
        <div class="col-6">
            <div class="border-bottom pb-2"><b>{{ __('payment_history') }}</b></div>
            @foreach ($pos->payments as $payment)              
            <div class="d-flex justify-content-between">
                    <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</span>
                    <span>{{ @__(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method))}}</span>
                    <span>{{ businessCurrency($pos->business_id) }} {{ $payment->amount }}</span>
                </div>
            @endforeach
            <div class="d-flex justify-content-between border-top">
                <b>{{ __('total_paid') }}</b>
                <b>{{ businessCurrency($pos->business_id) }} {{ $pos->payments->sum('amount')}}</b> 
            </div>
            <div class="d-flex justify-content-between  ">
                <b>{{ __('total_due') }}</b>
                <b>{{ businessCurrency($pos->business_id) }} {{ $pos->due_amount}}</b> 
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex justify-content-between">
                <b>{{ __('total_quantity') }}</b>
                <span>{{ $pos->posItems->sum('sale_quantity') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('sub_total') }}</b>
                <span>{{ businessCurrency($pos->business_id) }}  {{ $pos->posItems->sum('total_unit_price') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('order_tax') }}</b>
                <span>(+) {{ businessCurrency($pos->business_id) }}  {{ $pos->TotalTaxAmount }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('shipping_charge') }}</b>
                <span>(+) {{ businessCurrency($pos->business_id) }}  {{ $pos->shipping_charge }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('discount') }}</b>
                <span>(-) {{ businessCurrency($pos->business_id) }}  {{ $pos->discount_amount }}</span>
            </div>
            <div class="d-flex justify-content-between border-top">
                <b>{{ __('total') }}</b>
                <span>{{ businessCurrency($pos->business_id) }}  {{ $pos->total_sale_price}}</span>
            </div>
        </div>
    </div>
    <div class="text-center">
        <div class="d-inline-block">
             <div class="barcode mb-3">{!! @$pos->barcode !!} </div>
             <p class="invoice_no">{{ @$pos->invoice_no }}</p>
        </div>
    </div>
    <!-- Responsive Dashboard Table --> 
</div>

@push('styles')
    <link rel="stylesheet" href="{{static_asset('backend')}}/css/pos/sale-print.css">
@endpush 
@push('scripts')
    <script> 
        window.print();
    </script>
@endpush