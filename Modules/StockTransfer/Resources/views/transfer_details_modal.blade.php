<div class="row purchase_details_modal">
 
    <div class="col-lg-4">
        <div class="d-flex">
            <span><b>{{ __('business') }}</span></b>:  {{ @$stockTransfer->business->business_name }} 
        </div> 
        <b>{{ __('from_branch') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>: {{ @$stockTransfer->fromBranch->name }}
        </div>
        <div class="d-flex">
            <span>{{ __('email') }}</span>: {{ @$stockTransfer->fromBranch->email }}
        </div>
        <div class="d-flex">
            <span>{{ __('phone') }}</span>: {{ @$stockTransfer->fromBranch->phone }}
        </div>  
    </div>
    <div class="col-lg-4">
        <b>{{ __('to_branch') }}:</b><br>
        <div class="d-flex">
            <span>{{ __('name') }}</span>: {{ @$stockTransfer->toBranch->name }}
        </div>
        <div class="d-flex">
            <span>{{ __('email') }}</span>:  {{ @$stockTransfer->toBranch->email }}
        </div>
        <div class="d-flex">
            <span>{{ __('phone') }}</span>:  {{ @$stockTransfer->toBranch->phone }}
        </div> 
    </div> 
    <div class="col-lg-4">   
        <div class="d-flex">
            <span>{{ __('date') }}</span>: {{ \Carbon\Carbon::parse($stockTransfer->created_at)->format('d-m-Y h:i:s A') }}
        </div> 

        <div class="d-flex">
            <span>{{ __('transfer_no') }}</span>: {{ @$stockTransfer->transfer_no }}
        </div> 
        <div >
            <span>{{ __('status') }}</span>: {!! @$stockTransfer->my_status !!}
        </div>
        <div >
            <span>{{ __('total_amount') }}</span>:   {{ businessCurrency(business_id()) }} {{ $stockTransfer->total_amount }} 
        </div>
    </div>
</div>
<b>{{ __('transfered_items') }}:</b>
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
                    @foreach ($stockTransfer->transferItems as $item) 
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
                            <td  data-title="{{ __('quantity') }}"> {{ @$item->quantity }}</td> 
                            <td data-title="{{ __('unit_price') }}">{{ businessCurrency($stockTransfer->business_id) }} {{ @$item->unit_price }} </td>  
                           <td data-title="{{ __('total_unit_price') }}" >{{ businessCurrency($stockTransfer->business_id) }} {{ @$item->total_unit_price }}</div>
                            </td>  
                        </tr>   
                    @endforeach
                </tbody>
                <thead>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_item') }}</th>  
                        <th colspan="4" class="text-left">: <span id="total_item" class="text-white">{{ @$stockTransfer->transferItems->sum('quantity') }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_amount') }}</th>  
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($stockTransfer->transferItems->sum('total_unit_price'),2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('shipping_charge') }} ( + )</th>   
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ number_format($stockTransfer->shipping_charge,2) }}</span></th>   
                    </tr>
                    <tr class="border-bottom bg-primary text-white head align-middle ">
                        <th colspan="3" class="text-left">{{ __('total_amount') }} ( + ) {{ __('shipping_charge') }}</th>  
                        <th colspan="4" class="text-left">: {{ businessCurrency(business_id()) }} <span  class="text-white">{{ $stockTransfer->total_amount }}</span></th>   
                    </tr>
                </thead>
            </table>
        </div> 
    </div>
</div>