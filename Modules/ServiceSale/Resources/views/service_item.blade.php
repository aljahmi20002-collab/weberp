 
    <tr>
        <td  data-title="{{ __('service_name') }}">
            {{ @$service->name }}  <br> 
            <input type="hidden" value="{{ $service->id }}" name="service_items[{{ $randomNumber }}][id]"   />
            <input type="hidden" value="{{ $service->id  }}" name="service_unique_add[]"/>
        </td> 
        <td  data-title="{{ __('quantity') }}">
            <input type="text" class="form-control form--control quantity" name="service_items[{{ $randomNumber }}][quantity]" id="quantity_{{ $randomNumber }}" value="{{ old('quantity',1) }}" />
        </td> 
        <td data-title="{{ __('unit_price') }}">
            <input type="text" class="form-control form--control " name="service_items[{{ $randomNumber }}][unit_price]" id="unit_price_{{$randomNumber}}" value="{{ old('unit_price',@$service->price) }}" />
        </td>  
        <td data-title="{{ __('total_unit_price') }}" >
            <div id="total_unit_cost_{{$randomNumber}}" class="totalUnitCost">{{(@$service->price*1) }}</div>
        </td>  
        <td  data-title="{{ __('action') }}">
            <label class="purchase_variation_location_remove"> <i class="fa fa-trash text-danger"></i></label>
        </td> 
    </tr>   
    @include('servicesale::service_item_js')
 