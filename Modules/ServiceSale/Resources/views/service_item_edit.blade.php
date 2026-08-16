<tr>
    @php
        $randomNumber  = \Str::random(6);
    @endphp
    <td  data-title="{{ __('service_name') }}">  {{ @$item->service->name }}  
        <input type="hidden" value="{{ $item->service->id }}"  name="service_items[{{ $randomNumber }}][id]"   />
        <input type="hidden" value="{{ $item->service->id  }}" name="service_unique_add[]"/>
    </td> 
    <td  data-title="{{ __('quantity') }}">
        <input type="text" class="form-control form--control quantity" name="service_items[{{ $randomNumber }}][quantity]" id="quantity_{{ $randomNumber }}" value="{{ old('quantity',$item->sale_quantity) }}" />
    </td> 
    <td data-title="{{ __('unit_price') }}">
        <input type="text" class="form-control form--control " name="service_items[{{ $randomNumber }}][unit_price]" id="unit_price_{{$randomNumber}}" value="{{ old('unit_price',@$item->unit_price) }}" />
    </td>  
    <td data-title="{{ __('total_unit_price') }}" >
        <div id="total_unit_cost_{{$randomNumber}}" class="totalUnitCost">{{(@$item->unit_price*$item->sale_quantity) }}</div>
    </td>  
    <td  data-title="{{ __('action') }}">
       <label class="purchase_variation_location_remove"> <i class="fa fa-trash text-danger"></i></label>
    </td> 
</tr>   
@push('scripts')
    @include('servicesale::service_item_js')
@endpush