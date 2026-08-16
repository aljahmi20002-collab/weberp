@extends('backend.partials.master')
@section('title',__('stock_alert'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('products') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('product.index') }}">{{ __('products') }}</a> </li>
            <li> <a href="#">{{ __('stock_alert') }}</a> </li>
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
                        <div class="nav nav-pills justify-content-between" id="nav-tab" role="tablist">
                            <div class="d-flex">
                                <a href="{{ route('product.index') }}" class="nav-link " >{{ __('all_product') }}</a>
                                <a href="#" class="nav-link active"  ><i class="fa fa-alert"></i>{{ __('stock_alert') }}</a>
                            </div> 
                            <div>
                                @if(hasPermission('product_create'))
                                    <a  href="{{ route('product.create') }}" class="btn btn-primary float-right  "  data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                    data-bs-placement="top">
                                        <i class="fa fa-plus"></i> {{ __('add') }}
                                    </a>
                                @endif 
                            </div> 
                        </div> 
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active "  id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0"> 
                               @include('product::stock_alert_list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" id="get-stock-alert" data-url="{{ route('product.stock.alert.getdata') }}"/>
 
@endsection
@push('scripts')  
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>  
    <script src="{{ static_asset('backend') }}/js/product/stock_alert.js" ></script> 
@endpush
