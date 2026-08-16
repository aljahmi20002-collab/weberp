@php  $i=0; @endphp
@foreach ($poses as $pos)
    <tr>
        <td data-title="#">{{ ++$i }}</td>
        <td data-title="{{ __('invoice_no') }}">
            <a href="{{ route('pos.invoice.view',$pos->id) }}" class="text-primary"   data-bs-toggle="tooltip" title="{{ __('view') }}">{{ @$pos->invoice_no }}</a>
        </td>
        <td data-title="{{ __('branch') }}">{{ $pos->branch->name }}</td>
        <td data-title="{{ __('status') }}">{!! $pos->MyShippingStatus !!}</td>
        <td data-title="{{ __('total_selling_price') }}">{{ businessCurrency(business_id()) }} {{ @number_format($pos->TotalSalePrice,2) }}</td>
        <td data-title="{{ __('total_payments') }}">{{ businessCurrency(business_id()) }} {{ @number_format($pos->payments->sum('amount'),2) }}</td>
        <td data-title="{{ __('total_due') }}">{{ businessCurrency(business_id()) }} {{ @number_format($pos->DueAmount,2) }}</td>
    </tr>
@endforeach 
@if ($poses->count() <=0)    
<tr>
    <td colspan="6" class="text-center">
            {{ __('pos_was_not_found') }}
    </td>
</tr>
@endif