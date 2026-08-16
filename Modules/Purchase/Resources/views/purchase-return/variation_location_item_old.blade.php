<tr>
    @php
        $randomNumber  = \Str::random(6);
        $variation_location  =  \Modules\Product\Entities\VariationLocationDetails::find($return_item['id']);
    @endphp
    <td  data-title="{{ __('product_name') }}">
    {{ @$variation_location->product->name }} - {{ @$variation_location->ProductVariation->sub_sku }}<br>
    (<b>{{ @$variation_location->variation->name }}</b>: {{ @$variation_location->ProductVariation->name }})<br>
        <small>Current Stock: {{ @$variation_location->qty_available }} {{ @$variation_location->product->unit->short_name }}</small>
        <input type="hidden" value="{{ $return_item['id'] }}" name="variation_locations[{{ $randomNumber }}][id]"   />
        <input type="hidden" value="{{ $return_item['id'] }}" name="variation_item_unique_add[]"/>
    </td> 
    <td  data-title="{{ __('return_quantity') }}">
        <input type="text" class="form-control form--control quantity" name="variation_locations[{{ $randomNumber }}][quantity]" id="quantity_{{ $randomNumber }}" value="{{ old('quantity',$return_item['quantity']) }}" />
    </td> 
    <td data-title="{{ __('unit_price') }}">
        <input type="text" class="form-control form--control " name="variation_locations[{{ $randomNumber }}][unit_price]" id="unit_price_{{$randomNumber}}" value="{{ old('unit_price',@$return_item['unit_price']) }}" />
    </td>  
    <td data-title="{{ __('total_unit_price') }}" >
        <div id="total_unit_cost_{{$randomNumber}}" class="totalUnitCost">{{(@$return_item['unit_price']*$return_item['quantity']) }}</div>
    </td>  
    <td  data-title="{{ __('action') }}">
       <label class="purchase_variation_location_remove"> <i class="fa fa-trash text-danger"></i></label>
    </td> 
</tr>   
@push('scripts')
    @include('purchase::purchase-return.variation_location_item_js')
@endpush  