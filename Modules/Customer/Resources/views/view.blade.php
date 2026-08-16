@extends('backend.partials.master')
@section('title',__('customer_view'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('customer_view') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('customers.index') }}">{{ __('customer') }}</a> </li>
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
                            <button class="nav-link @if(!$request->customer_invoice && !$request->customer_payments) active @endif" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" data-title="{{ __('profile') }}">{{ __('profile') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($request->customer_invoice) active @endif" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" data-title="{{ __('sales_invoice') }}">{{ __('invoice') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" data-title="{{ __('sales_payments') }}">{{ __('payments') }}</button>
                        </li>  
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="pos-invoice-tab" data-bs-toggle="pill" data-bs-target="#pos-invoice" type="button" role="tab" aria-controls="pos-invoice" aria-selected="false" data-title="{{ __('pos_invoice') }}">{{ __('pos_invoice') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pos-payment-tab" data-bs-toggle="pill" data-bs-target="#pos-payment" type="button" role="tab" aria-controls="pos-payment" aria-selected="false" data-title="{{ __('pos_payment') }}">{{ __('pos_payment') }}</button>
                        </li>  
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="pills-servicesale-tab" data-bs-toggle="pill" data-bs-target="#pills-servicesale" type="button" role="tab" aria-controls="pills-servicesale" aria-selected="false" data-title="{{ __('service_sale_invoice') }}">{{ __('service_sale_invoice') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-servicesale-payment-tab" data-bs-toggle="pill" data-bs-target="#pills-servicesale-payment" type="button" role="tab" aria-controls="pills-servicesale-payment" aria-selected="false" data-title="{{ __('service_sale_invoice_payments') }}">{{ __('service_sale_Invoice_payments') }}</button>
                        </li>  
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    @include('customer::view-content.profile')
                    @include('customer::view-content.invoice')
                    @include('customer::view-content.payment_list')
                    @include('customer::view-content.pos_invoice')
                    @include('customer::view-content.pos_payment_list')
                    @include('customer::view-content.service_sale_invoice')
                    @include('customer::view-content.service_sale_payment_list')
                </div>  
            </div>
        </div>
    </div>
    <input type="hidden" type="hidden" id="get-customer-invoice" data-url="{{ route('customers.get.invoice',$customer->id) }}"/>
    <input type="hidden" type="hidden" id="get-customer-payment-history" data-url="{{ route('customers.get.payment.history',$customer->id) }}"/>
    <input type="hidden" type="hidden" id="get-customer-pos-invoice" data-url="{{ route('customers.get.pos.invoice',$customer->id) }}"/>
    <input type="hidden" type="hidden" id="get-customer-pos-payment-history" data-url="{{ route('customers.get.pos.payment.history',$customer->id) }}"/>
    <input type="hidden" type="hidden" id="get-customer-service-sale-invoice" data-url="{{ route('customers.get.servicesale.invoice',$customer->id) }}"/>
    <input type="hidden" type="hidden" id="get-customer-service-sale-payment-history" data-url="{{ route('customers.get.servicesale.payment.history',$customer->id) }}"/>
@endsection 

@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>   
<script src="{{ static_asset('backend') }}/js/customer/invoice_table.js" ></script>   
<script src="{{ static_asset('backend') }}/js/customer/payment_history_table.js" ></script>   
<script src="{{ static_asset('backend') }}/js/customer/pos_invoice_table.js" ></script>   
<script src="{{ static_asset('backend') }}/js/customer/pos_payment_history_table.js" ></script>   
<script src="{{ static_asset('backend') }}/js/customer/service_sale_invoice_table.js" ></script>   
<script src="{{ static_asset('backend') }}/js/customer/service_sale_payment_history_table.js" ></script>   
@endpush
