@extends('backend.partials.master')
@section('title',__('suplier_view'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('suplier_view') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('suppliers.index') }}">{{ __('supplier') }}</a> </li>
            <li class="active">  {{ __('view') }} </li>
        </ul>
    </div>
@endsection

@section('maincontent') 
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            
                <div class="card">
                    <ul class="nav nav-pills  " id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if(!$request->supplier_invoice && !$request->supplier_invoice && !$request->purchase_payment && !$request->return_invoice && !$request->return_payment) active @endif   " id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" data-title="{{ __('profile') }}">{{ __('profile') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($request->supplier_invoice) active @endif " id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" data-title="{{ __('purchase_invoice') }}">{{ __('invoice') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($request->purchase_payment) active @endif " id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" data-title="{{ __('invoice_payments') }}">{{ __('purchase_invoice_payments') }}</button>
                        </li>  
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($request->return_invoice) active @endif " id="return-invoice-tab" data-bs-toggle="pill" data-bs-target="#return-invoice" type="button" role="tab" aria-controls="return-invoice" aria-selected="false" data-title="{{ __('purchase_return_invoice') }}">{{ __('return_invoice') }}</button>
                        </li>  

                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($request->return_payment) active @endif " id="return-payment-tab" data-bs-toggle="pill" data-bs-target="#return-payment" type="button" role="tab" aria-controls="return-payment" aria-selected="false" data-title="{{ __('purchase_return_payment') }}">{{ __('return_payment') }}</button>
                        </li> 

                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent"> 
                    @include('supplier::view-content.profile')
                    @include('supplier::view-content.invoice')
                    @include('supplier::view-content.invoice_payment_list')
                    @include('supplier::view-content.return-invoice.return_invoice')
                    @include('supplier::view-content.return-invoice.return_invoice_payment_list') 
                </div>  
            </div>
        </div>
    </div>

    <input type="hidden" id="supplier-get-purchase-invoice"                 data-url="{{ route('suppliers.get.purchase.invoice',$supplier->id) }}"/>
    <input type="hidden" id="supplier-get-purchase-invoice-payment"         data-url="{{ route('suppliers.get.purchase.invoice.payment',$supplier->id) }}"/>
    <input type="hidden" id="supplier-get-purchase-return-invoice"          data-url="{{ route('suppliers.get.purchase.return.invoice',$supplier->id) }}"/>
    <input type="hidden" id="supplier-get-purchase-return-invoice-payment"  data-url="{{ route('suppliers.get.purchase.return.invoice.payment',$supplier->id) }}"/>

@endsection 

@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>   
    <script src="{{ static_asset('backend') }}/js/supplier/purchase_invoice_table.js" ></script>   
    <script src="{{ static_asset('backend') }}/js/supplier/purchase_invoice_payment_table.js" ></script>   
    <script src="{{ static_asset('backend') }}/js/supplier/purchase_return_invoice_table.js" ></script>   
    <script src="{{ static_asset('backend') }}/js/supplier/return_payment_table.js" ></script>   
@endpush
