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
                                    <button type="button" class="btn btn-sm btn-primary text-white" onclick="printOnlyDiv('sale_print')">Print</button> 
                                </div>
                            </div>
                        </div>
                        <div id="sale_print"> 
                            <div class="row"> 
                                <div class="col-12 mt-5"> 
                                    <h3 class="text-center"><b class="cmr-5">{{ @$sale->business->business_name }}</b> </h3>
                                    <p class="text-center mt-2"> {{ @$sale->branch->name }}, {{ @$sale->branch->state }} </p> 
                                    <h4 class="text-center  ">Invoice</h4>
                                    <div class="text-center"><small>( {{ __('sale') }} )</small></div>
                                    <div  class="text-right">
                                        <div class="d-inline-block"><span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse(@$sale->created_at)->format('d-m-Y h:i') }}</div>
                                    </div>
                                    <div class="mb-2 align-items-center print-info"> 
                                        <p>
                                            {{ __('invoice_no') }}</span>: {{ @$sale->invoice_no }}<br/> 
                                            <span>{{ __('shipping_status') }}</span>:   {{ __(\Config::get('pos_default.shpping_status.'.@$sale->shipping_status)) }}
                                        </p> 
                                        <p class=" "><b>Customer:</b></p> 
                                        <p>
                                            @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::WALK_CUSTOMER)
                                                {{ __(\Config::get('pos_default.customer_type.'.@$sale->customer_type))}}<br/>
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
                                        @foreach ($sale->saleItems as $item) 
                                            <tr>  
                                                <td > 
                                                <small> {{ @$item->variation_location->product->name }} - {{ @$item->variation_location->variation->name }} - {{ @$item->variation_location->ProductVariation->name }}</small>
                                                </td> 
                                                <td > 
                                                <small> {{ @$item->sale_quantity }}</small>
                                                </td> 
                                                <td > 
                                                <small>{{ businessCurrency($sale->business_id) }} {{ @number_format($item->unit_price,2) }}</small>
                                                </td> 
                                                <td > 
                                                <small>{{ businessCurrency($sale->business_id) }}  {{ @number_format($item->total_unit_price,2)  }}</small>
                                                </td> 
                                            </tr>
                                        @endforeach  
                                    </tbody>
                                </table>
                            </div> 
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="border-bottom pb-2"><b>{{ __('payment_history') }}</b></div>
                                    @foreach ($sale->payments as $payment)              
                                    <div class="d-flex justify-content-between">
                                            <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</span>
                                            <span>{{ @__(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method))}}</span>
                                            <span>{{ businessCurrency($sale->business_id) }} {{ number_format($payment->amount,2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="d-flex justify-content-between border-top  ">
                                        <b>{{ __('total_paid') }}</b>
                                        <b>{{ businessCurrency($sale->business_id) }} {{ number_format($sale->payments->sum('amount'),2)}}</b> 
                                    </div>
                                    <div class="d-flex justify-content-between  ">
                                        <b>{{ __('total_due') }}</b>
                                        <b>{{ businessCurrency($sale->business_id) }} {{ number_format($sale->due_amount,2)}}</b> 
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('total_quantity') }}</b>
                                        <span>{{ $sale->saleItems->sum('sale_quantity') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('sub_total') }}</b>
                                        <span>{{ businessCurrency($sale->business_id) }}  {{ number_format($sale->saleItems->sum('total_unit_price'),2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('order_tax') }}</b>
                                        <span>(+) {{ businessCurrency($sale->business_id) }}  {{ number_format($sale->TotalTaxAmount,2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('shipping_charge') }}</b>
                                        <span>(+) {{ businessCurrency($sale->business_id) }}  {{ number_format($sale->shipping_charge,2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('discount') }}</b>
                                        <span>(-) {{ businessCurrency($sale->business_id) }}  {{ number_format($sale->discount_amount,2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top">
                                        <b>{{ __('total') }}</b>
                                        <span>{{ businessCurrency($sale->business_id) }}  {{ number_format($sale->total_sale_price,2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="d-inline-block">
                                    <div class="barcode mb-3">{!! @$sale->barcode !!} </div>
                                    <p class="invoice_no">{{ @$sale->invoice_no }}</p>
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