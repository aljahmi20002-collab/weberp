<div class="row product-view" id="test"> 
    <div class="col-lg-4">
        <div class="d-flex">
            <b>{{ __('sku') }}:</b> {{ @$product->sku }}
        </div>
        <div class="d-flex">
            <b>{{ __('category') }}:</b> {{ @$product->category->name }}
        </div>
        <div class="d-flex">
            <b>{{ __('subcategory') }}:</b> {{ @$product->subcategory->name }}
        </div>
        <div class="d-flex">
            <b>{{ __('brand') }}:</b> {{ @$product->brand->name }}
        </div>

        <div class=" ">
            <b>{{ __('available_branch') }}:</b>  
            @foreach ($product->AllBranches as $branch)
                <span class="badge badge-primary badge-pill"> {{ $branch->name }}</span>
            @endforeach
        </div>  
    </div>

    <div class="col-lg-4">
        <div class="d-flex">
            <b>{{ __('warranty') }}:</b> {{ @$product->warranty->name }}
        </div> 
        <div class="d-flex">
            <b>{{ __('unit') }}:</b> {{ @$product->unit->name }}
        </div>
        <div class="d-flex">
            <b>{{ __('tax') }}:</b> {{ @$product->taxRate->name }}
        </div>
        <div class="d-flex">
            <b>{{ __('barcode_type') }}:</b>
            @foreach (\Config::get('pos_default.barcode_types') as $key=>$type )
                @if($key == $product->barcode_type)
                    {{ @__($type) }}
                @endif
            @endforeach 
        </div>
        <div class="d-flex">
            <b>{{ __('alert_quantity') }}:</b> {{ @$product->alert_quantity }} {{ @$product->unit->short_name }}
        </div>
        <div class="d-flex">
            <b>{{ __('total_quantity') }}:</b> {{ @$product->availableQuantity->sum('qty_available')  }}  {{ @$product->unit->short_name }}
        </div>
    </div> 
    <div class="col-lg-4 text-center"> 
        <div>
            <img class="img-thumbnail" src="{{ @$product->image }}" width="50%"/> 
        </div>
    </div>
    <div class="col-12">
        <div><b>{{ __('description') }}:</b></div>
        {!! @$product->description !!}
    </div>
</div>
<h5 class="mt-2">{{ __('variations') }}:</h5>
<div class="table-responsive table-responsive category-table product-view-table mt-2">
    <table class="table table-striped table-hover">
        <thead>
            <tr class="border-bottom bg-primary text-white head align-middle">
                <th>{{ __('variation') }}</th>
                <th>{{ __('sku') }}</th>
                <th>{{ __('purchase_price') }}</th>
                <th>{{ __('profit_percent') }} (%)</th>
                <th>{{ __('selling_price') }}</th>
                <th>
                    {{ __('tax_amount') }}<br>
                    <small>({{ @$product->taxRate->tax_rate }} %)</small>
                </th>
                <th>{{ __('selling_price_inc_tax') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($product->productVariations as $variation )            
            <tr>
                <td data-title="{{ __('variation') }}">{{ @$variation->variation->name }} - {{ $variation->name }}</td>
                <td data-title="{{ __('sku') }}"> {{ @$variation->sub_sku }}</td>
                <td data-title="{{ __('purchase_price') }}">{{ @businessCurrency($variation->product->business_id) }} {{ @$variation->default_purchase_price }}</td>
                <td data-title="{{ __('profit_percent') }}">{{ @$variation->profit_percent }}</td>
                <td data-title="{{ __('selling_price') }}">{{ @businessCurrency($variation->product->business_id) }} {{ @$variation->default_sell_price }}</td>
                <td data-title="{{ __('tax_amount') }}">{{ @businessCurrency($variation->product->business_id) }} {{ (@$variation->default_sell_price/100) * $product->taxRate->tax_rate }}</td>
                <td data-title="{{ __('selling_price_inc_tax') }}">{{ @businessCurrency($variation->product->business_id) }} {{ @$variation->sell_price_inc_tax }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<h5 class="mt-2">{{ __('product_stock_details') }}:</h5>
<div class="table-responsive table-responsive category-table product-view-table mt-2">
    <table class="table table-striped table-hover">
        <thead>
            <tr class="border-bottom bg-primary text-white head align-middle">
                <th>{{ __('sku') }}</th>
                <th>{{ __('product') }}</th>
                <th>{{ __('branch') }}</th>
                <th>{{ __('unit_price') }} (Inc.Tax)</th>
                <th>{{ __('current_stock') }}</th>
                <th>{{ __('current_stock_price') }} (Inc.Tax)</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($product->ProductVariationLocations as $variLocation )            
                <tr>
                    <td data-title="{{ __('sku') }}">{{ @$variLocation->ProductVariation->sub_sku }} </td>
                    <td data-title="{{ __('product') }}">{{ @$product->name }} - {{ @$variLocation->variation->name }} - {{ @$variLocation->ProductVariation->name }}</td>
                    <td data-title="{{ __('branch') }}">{{ @$variLocation->branch->name }} </td>
                    <td data-title="{{ __('unit_price') }}">{{ @businessCurrency($variLocation->product->business_id) }} {{ @$variLocation->ProductVariation->sell_price_inc_tax }} </td>
                    <td data-title="{{ __('current_stock') }}">{{ @$variLocation->qty_available }}  {{ @$product->unit->short_name }}</td>
                    <td data-title="{{ __('current_stock_price') }}">{{ @businessCurrency($variLocation->product->business_id) }} {{ $variLocation->CurrentStockPrice }} </td> 
                
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
 