@extends('pos::master')
@section('title',__('print'))
@section('maincontent')  
<div class="row printsbtn">
    <div class="col-12 text-right mt-3">
        <button class="btn btn-primary" type="button" onclick="window.print()"><i class="fa fa-print m-1"></i></button> 
    </div>
</div>
<div id="sale_print"> 
    <div class="row"> 
        <div class="col-12"> 
            <div class="text-center"> 
                <img src="{{  @$pos->business->LogoImg }}" class="m-0" alt="images">
            </div> 
            <p class="text-center mt-2"> {{ @$pos->branch->name }}, {{ @$pos->branch->state }} </p> 
            <h4 class="text-center mb-3 ">Invoice <small>( {{ __('pos') }} )</small></h4> 
          
            <div  class="text-left">
                <div class="d-inline-block"><b>{{ __('date') }}</b>: {{ \Carbon\Carbon::parse(@$pos->created_at)->format('d-m-Y h:i') }}</div>
            </div>
            <div class="align-items-center print-info"> 
                <p class="mb-0"> <b  >{{ __('invoice_no') }}</b>: {{ @$pos->invoice_no }} </p> 
                <p class="mb-0"><b>{{ __('shipping_status') }}</b>:   {{ __(\Config::get('pos_default.shpping_status.'.@$pos->shipping_status)) }}</p>
                 <p class="mb-0 mt-0"><b>Customer:</b></p> 
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
        <div class="border-bottom pb-2 text-center"><b>Item list</b></div>
        @php
            $i=0;
        @endphp
        @foreach ($pos->posItems as $item) 
            <div class="item" style="border-bottom:1px solid #00000024">
                <div><b>{{ __('product') }}:</b>{{ @$item->variation_location->product->name }} - {{ @$item->variation_location->variation->name }} - {{ @$item->variation_location->ProductVariation->name }}</div> 
                <div><b>{{ __('price') }}:</b> {{ @$item->sale_quantity  }} x {{ businessCurrency($pos->business_id) }}{{ @$item->unit_price }} =  {{ businessCurrency($pos->business_id) }}{{ @$item->total_unit_price  }}</div>   
            </div>
             
        @endforeach  
             
    </div>

    <div class="row mt-3">
   
        <div class="col-12">
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
        <div class="col-12 mb-3">
            <div class="border-bottom pb-2 text-center mt-3"><b>{{ __('payment_history') }}</b></div>
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
    </div>
    <div class="text-center">
        <div class="d-inline-block">
             <div class="barcode mb-3">{!! @$pos->barcode !!} </div>
             <p class="invoice_no">{{ @$pos->invoice_no }}</p>
        </div>
    </div>
    <!-- Responsive Dashboard Table --> 
</div>
 
@endsection 
@push('styles')
    <link rel="stylesheet" href="{{static_asset('backend')}}/css/pos/pos_print.css">
    <style>
        body{
            width: 80mm; 
            margin: auto; 
        }
        @media print {
            @page  {  
                size: Portrait; 
            
            }
            body{
                width: unset!important; 
                margin: unset!important;  
            }       
        } 

        .user-panel{
            padding:10px!important;
        }
    </style>
@endpush 
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/pos/pos.js"></script> 
@endpush