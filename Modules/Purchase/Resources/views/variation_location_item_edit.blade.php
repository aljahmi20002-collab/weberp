<tr>
    @php
        $randomNumber  = \Str::random(6);
    @endphp
    <td  data-title="{{ __('product_name') }}">
    {{ @$item->variation_location->product->name }} - {{ @$item->variation_location->ProductVariation->sub_sku }}<br>
    (<b>{{ @$item->variation_location->variation->name }}</b>: {{ @$item->variation_location->ProductVariation->name }})<br>
        <small>Current Stock: {{ @$item->variation_location->qty_available }} {{ @$item->variation_location->product->unit->short_name }}</small>
        <input type="hidden" value="{{ $item->variation_location->id }}" name="variation_locations[{{ $randomNumber }}][id]"   />
        <input type="hidden" value="{{ $item->variation_location->id }}" name="variation_item_unique_add[]"/>
    </td> 
    <td  data-title="{{ __('quantity') }}">
        <input type="text" class="form-control form--control quantity" name="variation_locations[{{ $randomNumber }}][quantity]" id="quantity_{{ $randomNumber }}" value="{{ old('quantity',@$item->purchase_quantity) }}" />
    </td> 
    <td data-title="{{ __('unit_cost') }}">
        <input type="text" class="form-control form--control " name="variation_locations[{{ $randomNumber }}][unit_cost]" id="unit_price_{{$randomNumber}}" value="{{ old('unit_cost',@$item->unit_cost) }}" />
    </td>  
     <td data-title="{{ __('total_unit_cost') }}" >
        <div id="total_unit_cost_{{$randomNumber}}" class="totalUnitCost">{{(@$item->unit_cost*$item->purchase_quantity) }}</div>
    </td>  
    <td  data-title="{{ __('profit_percent') }} ( % )">
        <input type="text" class="form-control form--control" value="{{ old('profit_percent',@$item->profit_percent) }}" name="variation_locations[{{ $randomNumber }}][profit_percent]"  id="profit_percent_{{ $randomNumber }}"/>
    </td> 
     @php 
        $profit_amount = ($item->unit_cost/100) * $item->profit_percent;
        $selling_price = $item->unit_cost + $profit_amount;
    @endphp
     <td data-title="{{ __('unit_selling_price') }}">
        <input type="text" class="form-control form--control" value="{{ old('unit_selling_price',@$selling_price) }}" name="variation_locations[{{ $randomNumber }}][unit_selling_price]"  id="selling_price_{{ $randomNumber }}"/>
    </td> 
    <td  data-title="{{ __('action') }}">
       <label class="purchase_variation_location_remove"> <i class="fa fa-trash text-danger"></i></label>
    </td>  
</tr>
@push('scripts')
    
@include('purchase::variation_location_item_edit_js')
@endpush  