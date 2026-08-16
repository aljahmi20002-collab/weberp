 
 @php
     $randomNumber  = \Str::random(5);
 @endphp
<tr>
    <td data-title="{{ __('product_name') }}">
    {{ @$item->variation_location->product->name }} - {{ @$item->variation_location->ProductVariation->sub_sku }}<br>
    (<b>{{ @$item->variation_location->variation->name }}</b>: {{ @$item->variation_location->ProductVariation->name }})<br>
        <small>Current Stock: <span class="qty_available{{ $item->variation_location->id }}">{{ @$item->variation_location->qty_available }}</span> {{ @$item->variation_location->product->unit->short_name }}</small>
        <input type="hidden" value="{{ $item->variation_location->id }}" class="variation_locations_array" name="variation_locations[{{ $randomNumber }}][id]" />
        <input type="hidden" value="{{ $item->variation_location->id }}" name="variation_item_unique_add[]"/>
    </td> 
    <td data-title="{{ __('quantity') }}">
        <input type="text" class="form-control form--control quantity qty{{ $item->variation_location->id }}" name="variation_locations[{{ $randomNumber }}][quantity]" id="quantity_{{ $randomNumber }}" value="{{ old('quantity',$item->sale_quantity) }}" />
    </td> 
    <td data-title="{{ __('unit_price') }}">
        <input type="text" class="form-control form--control unit_price{{ $item->variation_location->id }}" name="variation_locations[{{ $randomNumber }}][unit_price]" id="unit_price_{{$randomNumber}}" value="{{ old('unit_price',@$item->unit_price) }}"  />
    </td>  
    <td data-title="{{ __('total_unit_price') }}" >
        <div id="total_unit_cost_{{$randomNumber}}" class="totalUnitCost total_unit_price{{ $item->variation_location->id }}">{{(@$item->total_unit_price) }}</div>
    </td>  
    <td data-title="{{ __('action') }}">
        <label class="purchase_variation_location_remove"> <i class="fa fa-trash text-danger"></i></label>
    </td> 
</tr>
@push('scripts')
    @include('pos::variation_location_item_js')
@endpush
