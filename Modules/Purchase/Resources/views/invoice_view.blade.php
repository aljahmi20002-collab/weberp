@extends('backend.partials.master')
@section('title',__('purchase_invoice_view'))
 
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
                                    <h3 class="text-center"><b class="cmr-5">{{ @$purchase->business->business_name }}</b> </h3>
                                    <p class="text-center mt-2">  
                                        @foreach ($purchase->PurchasedBranch as $key=>$branch)
                                            {{ $branch->name }} @if(($key+1) < $purchase->PurchasedBranch->count()),@endif
                                        @endforeach
                                    </p> 
                                    <h4 class="text-center  ">Invoice</h4>
                                    <div class="text-center"><small>( {{ __('purchase') }} )</small></div>
                                    <div  class="text-right">
                                        <div class="d-inline-block"><span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse(@$purchase->created_at)->format('d-m-Y h:i') }}</div>
                                    </div>
                                    <div class="mb-2 align-items-center print-info"> 
                                        <p>
                                            {{ __('invoice_no') }}</span>: {{ @$purchase->purchase_no }}<br/> 
                                          
                                        </p> 
                                        <p class=" "><b>Supplier:</b></p> 
                                        <p>
                                            @if($purchase->customer_type == \Modules\Customer\Enums\CustomerType::WALK_CUSTOMER)
                                                {{ __(\Config::get('pos_default.customer_type.'.@$purchase->customer_type))}}<br/>
                                            @else
                                               {{ $purchase->supplier->company_name }}( {{ __('company') }})<br/>
                                                {{ $purchase->supplier->name }}<br/>
                                                {{ $purchase->supplier->phone }}<br/>
                                                {{ $purchase->supplier->email }}<br/>
                                                {{ $purchase->supplier->address }}
                                            @endif
                                        </p>
                                    </div>
                                </div> 
                            </div> 
                        
                            <!-- Responsive Dashboard Table -->
                            <div class="mt-2">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="border-bottom  align-middle">
                                            <th>{{ __('product_name') }}</th>
                                            <th>{{ __('quantity') }}</th>
                                            <th>{{ __('unit_cost') }}</th> 
                                            <th>{{ __('total_unit_cost') }}</th> 
                                            <th>{{ __('profit_percent') }} ( % ) </th> 
                                            <th>{{ __('unit_selling_price') }} (Exc.tax)</th>  
                                        </tr>
                                    </thead>
                                    <tbody id="purchase_item_body"> 
                                        @foreach ($purchase->purchaseItems as $item) 
                                            <tr>
                                                <td  data-title="{{ __('product_name') }}">
                                                    <div class="purchase_item_product_name  ">
                                                        <small>{{ @$item->variation_location->product->name }} - {{ @$item->variation_location->ProductVariation->sub_sku }}</small> 
                                                        <small>(<b>{{ @$item->variation_location->variation->name }}</b>: {{ @$item->variation_location->ProductVariation->name }})</small>
                                                      
                                                    </div>
                                                </td> 
                                                <td  data-title="{{ __('quantity') }}"> {{ @$item->purchase_quantity }}</td> 
                                                <td data-title="{{ __('unit_cost') }}">{{ businessCurrency($purchase->business_id) }} {{ @$item->unit_cost }} </td>  
                                               <td data-title="{{ __('total_unit_cost') }}" >{{ businessCurrency($purchase->business_id) }} {{ @$item->total_unit_cost }}</div>
                                                </td>  
                                              <td  data-title="{{ __('profit_percent') }} ( % )"> {{ @$item->profit_percent }}</td> 
                                                @php 
                                                    $profit_amount = ($item->unit_cost/100) * $item->profit_percent;
                                                    $selling_price = $item->unit_cost + $profit_amount;
                                                @endphp
                                                <td data-title="{{ __('unit_selling_price') }}">{{ businessCurrency($purchase->business_id) }} {{ $selling_price }}</td>    
                                            </tr>   
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="border-bottom pb-2"><b>{{ __('payment_history') }}</b></div>
                                    @foreach ($purchase->payments as $payment)              
                                    <div class="d-flex justify-content-between">
                                            <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</span>
                                            <span>{{ @__(\Config::get('pos_default.purchase.payment_method.'.$payment->payment_method))}}</span>
                                            <span>{{ businessCurrency($purchase->business_id) }} {{ number_format($payment->amount,2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="d-flex justify-content-between border-top  ">
                                        <b>{{ __('total_paid') }}</b>
                                        <b>{{ businessCurrency($purchase->business_id) }} {{ number_format($purchase->payments->sum('amount'),2)}}</b> 
                                    </div>
                                    <div class="d-flex justify-content-between  ">
                                        <b>{{ __('total_due') }}</b>
                                        <b>{{ businessCurrency($purchase->business_id) }} {{ number_format($purchase->due_amount,2)}}</b> 
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('total_quantity') }}</b>
                                        <span>{{ $purchase->purchaseItems->sum('purchase_quantity') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('sub_total') }}</b>
                                        <span>{{ businessCurrency($purchase->business_id) }}  {{ number_format($purchase->purchaseItems->sum('total_unit_cost'),2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <b>{{ __('order_tax') }}</b>
                                        <span>(+) {{ businessCurrency($purchase->business_id) }}  {{ number_format($purchase->TotalTaxAmount,2) }}</span>
                                    </div> 
                                    <div class="d-flex justify-content-between border-top">
                                        <b>{{ __('total') }}</b>
                                        <span>{{ businessCurrency($purchase->business_id) }}  {{ number_format($purchase->total_purchase_cost,2)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="d-inline-block">
                                    <div class="barcode mb-3">{!! @$purchase->barcode !!} </div>
                                    <p class="invoice_no">{{ @$purchase->purchase_no }}</p>
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
 