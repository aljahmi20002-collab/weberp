<div class="row purchase_details_modal">
    <div class="col-lg-3">
        <b>{{ __('customer') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('type') }}</span>: {{ __(\Config::get('pos_default.customer_type.'.$pos->customer_type)) }}
        </div> 
        @if($pos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
            <div class="d-flex">
                <span>{{ __('name') }}</span>: {{ @$pos->customer->name }}
            </div> 
            <div class="d-flex">
                <span>{{ __('email') }}</span>: {{ @$pos->customer->email }}
            </div>
            <div class="d-flex">
                <span>{{ __('phone') }}</span>: {{ @$pos->customer->phone }}
            </div>
            <div class="d-flex">
                <span>{{ __('address') }}</span>: <span class="purchase_address"> {{ @$pos->customer->address }}</span>
            </div>
        @endif
    </div>

    <div class="col-lg-3">
        <b>{{ __('business') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>:  {{ @$pos->business->business_name }} 
        </div>
        <div class="d-flex" >
            <span>{{ __('branch') }}</span>:  {{ @$pos->branch->name }}
        </div>
    </div>
 
    <div class="col-lg-3">   
        <div class="d-flex">
            <span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse($pos->created_at)->format('d-m-Y h:i:s A') }}
        </div>
        <div class="d-flex">
            <span>{{ __('invoice_no') }}</span>: {{ @$pos->invoice_no }}
        </div>
        <div class="d-flex">
            <span>{{ __('order_tax') }}</span>: {{ @$pos->TaxRate->name }}
        </div> 
        <div   >
            <span>{{ __('shipping_status') }}</span>:   {{ __(\Config::get('pos_default.shpping_status.'.$pos->shipping_status)) }}
        </div>
        <div  >
            <span>{{ __('payment_status') }}</span>:
            {!! @$pos->my_payment_status !!}
        </div>
    </div>

    <div class="col-lg-3">   
        <table class="table paymentview-table" width="100">
            <tr>
                <td colspan="2" class="border-top"><b>{{ __('payment_history') }}</b></td> 
            </tr>
            @foreach ($pos->payments as $payment)   
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y')}}</td>
                <td>{{ @businessCurrency($payment->pos->business_id) }} {{ @number_format($payment->amount,2) }}</td>
            </tr> 
            @endforeach
            <tr>
                <td><b>{{ __('total_paid') }}</b>:</td>
                <td><b>{{ @businessCurrency($pos->business_id) }}  {{ @number_format($pos->payments->sum('amount'),2) }}</b></td>
            </tr> 
        </table>  
    </div>

</div>
<b>{{ __('pos_items') }}:</b>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive   category-table product-view-table mt-2">
            <table class="table table-striped table-hover text-left">
                <thead>
                    <tr class="border-bottom bg-primary text-white head align-middle">
                        <th>{{ __('product_name') }}</th>
                        <th>{{ __('quantity') }}</th>
                        <th>{{ __('unit_price') }}</th> 
                        <th>{{ __('total_unit_price') }}</th>  
                    </tr>
                </thead>
                <tbody id="sell_item_body"> 
                    @foreach ($pos->posItems as $item) 
                        <tr>
                            <td data-title="{{ __('product_name') }}">
                                <div class="purchase_item_product_name  ">
                                    <div>{{ @$item->variation_location->product->name }} - {{ @$item->variation_location->ProductVariation->sub_sku }}</div> 
                                    <div>(<b>{{ @$item->variation_location->variation->name }}</b>: {{ @$item->variation_location->ProductVariation->name }})</div>
                                    <div>
                                        <small>Current Stock: {{ @$item->variation_location->qty_available }} {{ @$item->variation_location->product->unit->short_name }}</small> 
                                    </div>
                                </div>
                            </td> 
                            <td  data-title="{{ __('quantity') }}"> {{ @$item->sale_quantity }}</td> 
                            <td data-title="{{ __('unit_price') }}">{{ businessCurrency($pos->business_id) }} {{ @$item->unit_price }} </td>  
                           <td data-title="{{ __('total_unit_price') }}" >{{ businessCurrency($pos->business_id) }} {{ @$item->total_unit_price }}</div>
                            </td>  
                        </tr>   
                    @endforeach
                </tbody>
                <thead>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_item') }}</th>  
                        <th colspan="4" class="text-left">: <span id="total_item" class="text-white">{{ @$pos->posItems->sum('sale_quantity') }}</span></th>   
                    </tr> 
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_sale_amount') }}</th>  
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($pos->posItems->sum('total_unit_price'),2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('order_tax') }} ( + )</th>  
                        @php
                            $taxAmount =($pos->posItems->sum('total_unit_price')/100) * $pos->TaxRate->tax_rate;
                        @endphp
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($taxAmount,2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('shipping_charge') }} ( + )</th>   
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($pos->shipping_charge,2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('discount_amount') }} ( - )</th>   
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($pos->discount_amount,2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_sell_amount') }} ( + ) All</th>  
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($pos->total_sale_price,2) }}</span></th>   
                    </tr>
                </thead>
            </table>
        </div> 
    </div>
</div>