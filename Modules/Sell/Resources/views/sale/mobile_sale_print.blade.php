@extends('sell::sale.layouts.master')
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
                <img src="{{  @$sale->business->LogoImg }}" class="m-0" alt="images">
            </div> 
            <p class="text-center mt-2"> {{ @$sale->branch->name }}, {{ @$sale->branch->state }} </p> 
            <h4 class="text-center mb-3 ">Invoice <small>( {{ __('sale') }} )</small></h4> 
          
            <div  class="text-left">
                <div class="d-inline-block"><b>{{ __('date') }}</b>: {{ \Carbon\Carbon::parse(@$sale->created_at)->format('d-m-Y h:i') }}</div>
            </div>
            <div class="align-items-center print-info"> 
                <p class="mb-0"> <b  >{{ __('invoice_no') }}</b>: {{ @$sale->invoice_no }} </p> 
                <p class="mb-0"><b>{{ __('shipping_status') }}</b>:   {{ __(\Config::get('pos_default.shpping_status.'.@$sale->shipping_status)) }}</p>
                 <p class="mb-0 mt-0"><b>Customer:</b></p> 
                 <p>
                    @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::WALK_CUSTOMER)
                        {{ __(\Config::get('pos_default.customer_type.'.@$sale->customer_type))}}<br/>
                        {{ $sale->customer_phone}}<br/>
                    @else
                        {{ $sale->customer->name }}<br/>
                        {{ $sale->customer->phone }}<br/>
                        {{ $sale->customer->email }}<br/>
                        {{ $sale->customer->address }}
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
        @foreach ($sale->saleItems as $item) 
            <div class="item" style="border-bottom:1px solid #00000024">
                <div><b>{{ __('product') }}:</b>{{ @$item->variation_location->product->name }} - {{ @$item->variation_location->variation->name }} - {{ @$item->variation_location->ProductVariation->name }}</div> 
                <div><b>{{ __('price') }}:</b> {{ @$item->sale_quantity  }} x {{ businessCurrency($sale->business_id) }}{{ @$item->unit_price }} =  {{ businessCurrency($sale->business_id) }}{{ @$item->total_unit_price  }}</div>   
            </div>
             
        @endforeach  
             
    </div>

    <div class="row mt-3">
   
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <b>{{ __('total_quantity') }}</b>
                <span>{{ $sale->saleItems->sum('sale_quantity') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('sub_total') }}</b>
                <span>{{ businessCurrency($sale->business_id) }}  {{ $sale->saleItems->sum('total_unit_price') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('order_tax') }}</b>
                <span>(+) {{ businessCurrency($sale->business_id) }}  {{ $sale->TotalTaxAmount }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('shipping_charge') }}</b>
                <span>(+) {{ businessCurrency($sale->business_id) }}  {{ $sale->shipping_charge }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <b>{{ __('discount') }}</b>
                <span>(-) {{ businessCurrency($sale->business_id) }}  {{ $sale->discount_amount }}</span>
            </div>
            <div class="d-flex justify-content-between border-top">
                <b>{{ __('total') }}</b>
                <span>{{ businessCurrency($sale->business_id) }}  {{ $sale->total_sale_price}}</span>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="border-bottom pb-2 text-center mt-3"><b>{{ __('payment_history') }}</b></div>
            @foreach ($sale->payments as $payment)              
            <div class="d-flex justify-content-between">
                    <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</span>
                    <span>{{ @__(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method))}}</span>
                    <span>{{ businessCurrency($sale->business_id) }} {{ $payment->amount }}</span>
                </div>
            @endforeach
            <div class="d-flex justify-content-between border-top">
                <b>{{ __('total_paid') }}</b>
                <b>{{ businessCurrency($sale->business_id) }} {{ $sale->payments->sum('amount')}}</b> 
            </div>
            <div class="d-flex justify-content-between  ">
                <b>{{ __('total_due') }}</b>
                <b>{{ businessCurrency($sale->business_id) }} {{ $sale->due_amount}}</b> 
            </div>
        </div>
    </div>
    <div class="text-center">
        <div class="d-inline-block">
             <div class="barcode mb-3">{!! @$sale->barcode !!} </div>
             <p class="invoice_no">{{ @$sale->invoice_no }}</p>
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