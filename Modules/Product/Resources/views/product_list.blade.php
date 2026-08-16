@php
$i=0;
@endphp
@if(count($products) >0)
    @foreach ($products as $product)   
        <tr>
            <td data-title="#">{{ ++$i }}</td>   
             
        </tr>  
    @endforeach
@else
    <tr>
        <td colspan="13">{{ __('product_was_not_found') }}</td>
    </tr>
@endif
    