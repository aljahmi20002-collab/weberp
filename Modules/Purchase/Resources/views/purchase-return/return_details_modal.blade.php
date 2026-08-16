<div class="row purchase_details_modal">
    <div class="col-lg-3">
        <b>{{ __('supplier') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>: {{ @$purchase_return->supplier->name }}
        </div>
        <div class="d-flex">
            <span>{{ __('company') }}</span>: {{ @$purchase_return->supplier->company_name }}
        </div>
        <div class="d-flex">
            <span>{{ __('email') }}</span>: {{ @$purchase_return->supplier->email }}
        </div>
        <div class="d-flex">
            <span>{{ __('phone') }}</span>: {{ @$purchase_return->supplier->phone }}
        </div>
        <div class="d-flex">
            <span>{{ __('address') }}</span>: <span class="purchase_address"> {{ @$purchase_return->supplier->address }}</span>
        </div>
    </div>
    <div class="col-lg-3">
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
    <div class="col-lg-3">   
        <div class="d-flex">
            <span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse($purchase_return->created_at)->format('d-m-Y h:i:s A') }}
        </div>
        <div class="d-flex">
            <span>{{ __('return_no') }}</span>: {{ @$purchase_return->return_no }}
        </div>
        <div class="d-flex">
            <span>{{ __('purchase_tax') }}</span>: {{ @$purchase_return->TaxRate->name }}
        </div> 
        <div >
            <span>{{ __('payment_status') }}</span>: {!! @$purchase_return->my_payment_status !!}
        </div>
    </div>
    <div class="col-lg-3">   
        <table class="table paymentview-table" width="100">
            <tr>
                <td colspan="2" class="border-top"><b>{{ __('payment_history') }}</b></td> 
            </tr>
            @foreach ($purchase_return->payments as $payment)   
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->paid_date)->format('d-m-Y')}}</td>
                <td>{{ @businessCurrency($payment->purchase->business_id) }} {{ @number_format($payment->amount,2) }}</td>
            </tr> 
            @endforeach
            <tr>
                <td><b>{{ __('total_paid') }}</b>:</td>
                <td><b>{{ @businessCurrency($purchase_return->business_id) }}  {{ @number_format($purchase_return->payments->sum('amount'),2) }}</b></td>
            </tr> 
        </table>  
    </div>
</div>
<b>{{ __('purchase_return_items') }}:</b>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive  category-table product-view-table mt-2">
            <table class="table table-striped table-hover text-left">
                <thead>
                    <tr class="border-bottom bg-primary text-white head align-middle">
                        <th>{{ __('product_name') }}</th>
                        <th>{{ __('quantity') }}</th>
                        <th>{{ __('unit_price') }}</th> 
                        <th>{{ __('total_unit_price') }}</th>  
                    </tr>
                </thead>
                <tbody id="purchase_item_body"> 
                    @foreach ($purchase_return->purchaseReturnItems as $item) 
                        <tr>
                            <td  data-title="{{ __('product_name') }}">
                                <div class="purchase_item_product_name  ">
                                    <div>{{ @$item->variation_location->product->name }} - {{ @$item->variation_location->ProductVariation->sub_sku }}</div> 
                                    <div>(<b>{{ @$item->variation_location->variation->name }}</b>: {{ @$item->variation_location->ProductVariation->name }})</div>
                                    <div>
                                        <small>Current Stock: {{ @$item->variation_location->qty_available }} {{ @$item->variation_location->product->unit->short_name }}</small> 
                                    </div>
                                </div>
                            </td> 
                            <td  data-title="{{ __('quantity') }}"> {{ @$item->return_quantity }}</td> 
                            <td data-title="{{ __('unit_price') }}">{{ businessCurrency($purchase_return->business_id) }} {{ @$item->unit_price }} </td>  
                           <td data-title="{{ __('total_unit_price') }}" >{{ businessCurrency($purchase_return->business_id) }} {{ @$item->total_unit_price }}</div>
                            </td>  
                        </tr>   
                    @endforeach
                </tbody>
                <thead>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_item') }}</th>  
                        <th colspan="4" class="text-left">: <span id="total_item" class="text-white">{{ @$purchase_return->purchaseReturnItems->sum('return_quantity') }}</span></th>   
                    </tr>

                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_return_amount') }}</th>  
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($purchase_return->purchaseReturnItems->sum('total_unit_price'),2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_purchase_tax') }} ( + )</th>  
                        @php
                            $taxAmount =($purchase_return->purchaseReturnItems->sum('total_unit_price')/100) * $purchase_return->TaxRate->tax_rate;
                        @endphp
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($taxAmount,2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_return_amount') }} ( + ) Tax</th>  
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($purchase_return->purchaseReturnItems->sum('total_unit_price')+$taxAmount,2) }}</span></th>   
                    </tr>
                </thead>
            </table>
        </div> 
    </div>
</div>