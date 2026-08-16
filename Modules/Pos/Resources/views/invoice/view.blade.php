@extends('backend.partials.master')
@section('title',__('invoice_view'))
 
@section('maincontent') 
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">  
                        <div class="row print-row">
                            <div class="col-12 text-right mt-2">
                                <div class="d-inline-block">
                                    <button type="button" class="btn btn-sm btn-primary text-white" onclick="printOnlyDiv('sale_print')">{{ __('print') }}</button> 
                                </div>
                            </div>
                        </div>
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
                                        <p class=" "><b>Customer:</b></p> 
                                        <p>
                                            @if($pos->customer_type == \Modules\Customer\Enums\CustomerType::WALK_CUSTOMER)
                                                {{ __(\Config::get('pos_default.customer_type.'.@$pos->customer_type))}}<br/>
                                                {{ __('phone') }}: {{ $pos->customer_phone}}<br/>
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
                                                <small>{{ businessCurrency($pos->business_id) }} {{ @number_format($item->unit_price,2) }}</small>
                                                </td> 
                                                <td > 
                                                <small>{{ businessCurrency($pos->business_id) }}  {{ @number_format($item->total_unit_price,2)  }}</small>
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
                                            <span>{{ businessCurrency($pos->business_id) }} {{ number_format($payment->amount,2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="d-flex justify-content-between border-top  ">
                                        <b>{{ __('total_paid') }}</b>
                                        <b>{{ businessCurrency($pos->business_id) }} {{ number_format($pos->payments->sum('amount'),2)}}</b> 
                                    </div>
                                    <div class="d-flex justify-content-between  ">
                                        <b>{{ __('total_due') }}</b>
                                        <b>{{ businessCurrency($pos->business_id) }} {{ number_format($pos->due_amount,2)}}</b> 
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('total_quantity') }}</b>
                                        <span>{{ $pos->posItems->sum('sale_quantity') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('sub_total') }}</b>
                                        <span>{{ businessCurrency($pos->business_id) }}  {{ number_format($pos->posItems->sum('total_unit_price'),2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('order_tax') }}</b>
                                        <span>(+) {{ businessCurrency($pos->business_id) }}  {{ number_format($pos->TotalTaxAmount,2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('shipping_charge') }}</b>
                                        <span>(+) {{ businessCurrency($pos->business_id) }}  {{ number_format($pos->shipping_charge,2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('discount') }}</b>
                                        <span>(-) {{ businessCurrency($pos->business_id) }}  {{ number_format($pos->discount_amount,2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top">
                                        <b>{{ __('total') }}</b>
                                        <span>{{ businessCurrency($pos->business_id) }}  {{ number_format($pos->total_sale_price,2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="d-inline-block">
                                    <div class="barcode mb-3">{!! @$pos->barcode !!} </div>
                                    <p class="invoice_no">{{ @$pos->invoice_no }}</p>
                                </div>
                            </div>
                            <!-- Responsive Dashboard Table --> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>  
@endsection 
@push('styles')
    <link rel="stylesheet" href="{{static_asset('backend')}}/css/sale/sale-print.css"> 
@endpush  