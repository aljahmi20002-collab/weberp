@php  $i=0; @endphp
@foreach ($sales as $sale)
    <tr>
        <td data-title="#">{{ ++$i }}</td>
        <td data-title="{{ __('invoice_no') }}">
            <a href="{{ route('sale.print', $sale->id) }}" target="_blank" class="text-primary" data-bs-toggle="tooltip"
                title="{{ __('view') }}">{{ @$sale->invoice_no }}</a>
        </td>
        <td data-title="{{ __('branch') }}">{{ $sale->branch->name }}</td>
        <td data-title="{{ __('status') }}">{!! $sale->MyShippingStatus !!}</td>
        <td data-title="{{ __('total_selling_price') }}">{{ businessCurrency(business_id()) }}
            {{ @number_format($sale->TotalSalePrice, 2) }}</td>
        <td data-title="{{ __('total_payments') }}">{{ businessCurrency(business_id()) }}
            {{ @number_format($sale->payments->sum('amount'), 2) }}</td>
        <td data-title="{{ __('total_due') }}">{{ businessCurrency(business_id()) }}
            {{ @number_format($sale->DueAmount, 2) }}</td>
    </tr>
@endforeach
@if ($sales->count() <= 0)
    <tr>
        <td colspan="6" class="text-center">
            {{ __('sale_was_not_found') }}
        </td>
    </tr>
@endif
