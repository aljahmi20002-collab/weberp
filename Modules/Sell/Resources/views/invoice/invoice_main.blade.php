@extends('backend.partials.master')
@section('title')
    @if($request->pos_page)
        {{ __('pos_invoice_list') }}
    @elseif ($request->purchase_page)
        {{ __('purchase_invoice_list') }}
    @elseif ($request->purchase_return_page)
        {{ __('purchase_return_invoice_list') }}
    @else
        {{ __('sale_invoice_list') }}
    @endif
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('invoice') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="#">{{ __('accounts') }}</a> </li>
            <li> <a href="#">{{ __('invoice') }}</a> </li>
            <li class="active">  {{ __('list') }} </li>
        </ul>
    </div>
@endsection  
@section('maincontent') 
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget "> 

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link invoice-btn 
                                @if( 
                                    !$request->pos_page &&
                                    !$request->purchase_page &&
                                    !$request->purchase_return_page) active @endif
                            " id="pills-sale-invoice-tab" data-bs-toggle="pill" data-bs-target="#pills-sale-invoice" type="button" role="tab" aria-controls="pills-sale-invoice" aria-selected="true" data-title="{{ __('sale_invoice_list') }}">{{ __('sale_invoice') }}</button>
                        </li>
 
                        <li class="nav-item " role="presentation">
                            <button class="nav-link invoice-btn @if($request->pos_page) active @endif" id="pills-pos-invoice-tab" data-bs-toggle="pill" data-bs-target="#pills-pos-invoice" type="button" role="tab" aria-controls="pills-pos-invoice" aria-selected="false"  data-title="{{ __('pos_invoice_list') }}">{{ __('pos_invoice') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  " id="pills-servicesale-invoice-tab" data-bs-toggle="pill" data-bs-target="#pills-servicesale-invoice" type="button" role="tab" aria-controls="pills-servicesale-invoice" aria-selected="true" data-title="{{ __('servicesale_invoice_list') }}">{{ __('servicesale_invoice') }}</button>
                        </li>
                        <li class="nav-item " role="presentation">
                            <button class="nav-link invoice-btn @if($request->purchase_page) active @endif" id="pills-purchase-invoice-tab" data-bs-toggle="pill" data-bs-target="#pills-purchase-invoice" type="button" role="tab" aria-controls="pills-purchase-invoice" aria-selected="false"  data-title="{{ __('purchase_invoice_list') }}">{{ __('purchase_invoice') }}</button>
                        </li>
                        <li class="nav-item " role="presentation">
                            <button class="nav-link invoice-btn @if($request->purchase_return_page) active @endif" id="pills-purchase-return-invoice-tab" data-bs-toggle="pill" data-bs-target="#pills-purchase-return-invoice" type="button" role="tab" aria-controls="pills-purchase-return-invoice" aria-selected="false"  data-title="{{ __('purchase_return_invoice_list') }}">{{ __('purchase_return_invoice') }}</button>
                        </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent"> 
                            @include('sell::invoice.index') 
                            @include('pos::invoice.index') 
                            @include('servicesale::invoice.index') 
                            @include('purchase::invoice.index')
                            @include('purchase::purchase-return.invoice.index')
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" id="get-sale-invoices" data-url="{{ route('invoice.sale.get.all') }}"/>
    <input type="hidden" id="get-pos-invoices" data-url="{{ route('pos.invoice.get.all') }}"/>
    <input type="hidden" id="get-servicesale-invoices" data-url="{{ route('servicesale.invoice.get.all') }}"/>
    <input type="hidden" id="get-purchase-invoices" data-url="{{ route('purchase.invoice.get.all') }}"/>
     <input type="hidden" id="get-purchase-return-invoices" data-url="{{ route('purchase.return.invoice.get.all') }}"/>
@endsection 
 
@push('scripts')  
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
<script src="{{ static_asset('backend') }}/js/invoice/sale_invoice_table.js" ></script>
<script src="{{ static_asset('backend') }}/js/invoice/pos_invoice_table.js" ></script>
<script src="{{ static_asset('backend') }}/js/invoice/servicesale_invoice_table.js" ></script>
<script src="{{ static_asset('backend') }}/js/invoice/purchase_invoice_table.js" ></script>
<script src="{{ static_asset('backend') }}/js/invoice/purchase_return_invoice_table.js" ></script>

@endpush
