<!-- Responsive Dashboard Table -->
<div class="  category-table">
    <table class="table table-striped table-hover">
        <thead>
            <tr class="border-bottom">
                <th>#</th>   
                <th>{{ __('sku') }}</th>    
                <th>{{ __('product') }}</th>   
                <th>{{ __('branch') }}</th>  
                <th>{{ __('variation') }}</th>   
                <th>{{ __('category') }}</th>   
                <th>{{ __('purchase_price') }}</th>  
                <th>{{ __('selling_price') }} <br>(Inc.Tax)</th>  
                <th>{{ __('current_stock_selling_price') }}<br> (Inc.Tax)</th>  
                <th>{{ __('profit_percent') }} (%)</th>  
                <th>{{ __('current_stock') }}</th>   
            </tr>
        </thead> 

        <tbody  >
            @php
                $i=0;
            @endphp
            @foreach ($ProductVariationLocations as $variationLocation)  
                <tr class="{{ @$variationLocation->product->alert_quantity >= @$variationLocation->qty_available ? 'stockalert-danger':'' }}">
                    <td data-title="#">{{ ++$i }}</td>   
                    <td data-title="{{ __('sku') }}">{{ @$variationLocation->ProductVariation->sub_sku }}</td>    
                    <td class="text-left" data-title="{{ __('product') }}">{{ @$variationLocation->product->name }}</td> 
                    <td data-title="{{ __('branch') }}"> {{ @$variationLocation->branch->name }} </td>    
                    <td data-title="{{ __('variation') }}"> {{ @$variationLocation->ProductVariation->name }} </td>    
                        <td data-title="{{ __('category') }}">
                        {{ @$variationLocation->product->category->name }}<br/>
                        --{{ @$variationLocation->product->subcategory->name }}
                    </td>     
                    <td data-title="{{ __('purchase_price') }}"> {{ businessCurrency($variationLocation->product->business_id) }} {{ @$variationLocation->ProductVariation->default_purchase_price }}</td>    
                    <td data-title="{{ __('selling_price') }} (Inc.Tax)"> {{ businessCurrency($variationLocation->product->business_id) }} {{ @$variationLocation->ProductVariation->sell_price_inc_tax }}</td>    
                    <td data-title="{{ __('current_stock_selling_price') }} (Inc.Tax)"> {{ businessCurrency($variationLocation->product->business_id) }} {{ @number_format($variationLocation->ProductVariation->sell_price_inc_tax * $variationLocation->qty_available,2) }}</td>    
                    <td data-title="{{ __('profit_percent') }} (%)"> {{  @$variationLocation->ProductVariation->profit_percent }}</td>    
                    <td data-title="{{ __('current_stock') }}"> {{ @$variationLocation->qty_available}} {{ $variationLocation->product->unit->short_name }}</td>   
                </tr>
                @endforeach 

        </tbody>
    </table> 
    <div class="stock_report_print mt-2">
        <div class=" d-flex justify-content-between py-1 ">
            <div colspan="10" class="text-left"><b>{{ __('total_stock_selling_price') }}</b></div> 
            <div><b>{{ businessCurrency(business_id()) }}  <span class="total_stock_selling_price">{{ @$data['total_calculation']->original['total_current_stock_selling_price'] }}</span></b></div>
        </div>
        <div class=" d-flex justify-content-between py-1 ">
            <div colspan="10" class="text-left "><b>{{ __('total_stock_purchase_price') }}</b></div>
            <div><b>{{ businessCurrency(business_id()) }}  <span class="total_stock_purchase_price">{{ @$data['total_calculation']->original['total_current_stock_purchase_price'] }}</span></b></div>
        </div>
        <div class=" d-flex justify-content-between py-1 ">
            <div colspan="10" class="text-left "><b>{{ __('total_stock_gross_profit') }}</b></div>
            <div><b>{{ businessCurrency(business_id()) }}  <span class="total_stock_gross_profit">{{ @$data['total_calculation']->original['total_current_stock_gross_profit'] }}</span></b></div>
        </div>
        <div class=" d-flex justify-content-between py-1 ">
            <div colspan="10" class="text-left "><b>{{ __('total_stock_tax_amount') }}</b></div>
            <div><b>{{ businessCurrency(business_id()) }}  <span class="total_stock_tax_amount">{{ @$data['total_calculation']->original['total_current_stock_tax'] }}</span></b></div>
        </div>
    </div> 
</div>
<!-- Responsive Dashboard Table -->
<!-- Pagination -->
<div class="d-flex flex-row-reverse align-items-center pagination-content">
    <span class="paginate pagination-number-link">{{ $ProductVariationLocations->links() }}</span>
    <p class="p-2 small paginate ">
        {!! __('Showing') !!}
        <span class="font-medium">{{ $ProductVariationLocations->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-medium">{{ $ProductVariationLocations->lastItem() }}</span>
        {!! __('of') !!}
        <span class="font-medium">{{ $ProductVariationLocations->total() }}</span>
        {!! __('results') !!}
    </p>
</div>
<!-- Pagination --> 
<script src="{{static_asset('backend')}}/js/reports/stock_report/stock_report_pagination.js"></script> 
 