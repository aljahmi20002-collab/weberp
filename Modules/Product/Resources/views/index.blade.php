@extends('backend.partials.master')
@section('title',__('all_product'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('products') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('product.index') }}">{{ __('products') }}</a> </li>
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
                                <a href="#" class="nav-link active" >{{ __('all_product') }}</a>
                                <a href="{{ route('product.stock.alert') }}" class="nav-link"  ><i class="fa fa-alert"></i>{{ __('stock_alert') }}</a>
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
                        <div class="tab-content pt-5" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">  
                                <!-- Responsive Dashboard Table -->
                                <div class="table-responsive category-table">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr class="border-bottom">
                                                <th>#</th>   
                                                <th>{{ __('image') }}</th>  
                                                <th>{{ __('name') }}</th>   
                                                <th>{{ __('sku') }}</th>  
                                                <th>{{ __('branch') }}</th>  
                                                <th>{{ __('purchase_price') }}</th>  
                                                <th>{{ __('selling_price') }}<br/>(Exc.Tax)</th>   
                                                <th>{{ __('available_quantity') }}</th>  
                                                <th>{{ __('category') }}</th>  
                                                <th>{{ __('brand') }}</th>  
                                                <th>{{ __('warranty') }}</th>   
                                                <th>{{ __('action') }}</th> 
                                            </tr>
                                        </thead>  
                                        <tbody id="product_body">  
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">
                                                    <div class="text-center">
                                                        <img class="emptyTables" src="{{settings('table_search_image') }}" width="20%" >
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Responsive Dashboard Table -->
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" id="get-products" data-url="{{ route('product.get.all.products') }}"/>
@endsection
@push('scripts')  
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script> 
    <script src="{{ static_asset('backend') }}/js/product/index.js" ></script> 
@endpush

@push('search-input')
      <script type="text/javascript">
        $(document).ready(function(){
            $('.export-row .search-box').html('<input type="text" id="product-search" class="form-control form--control table-search" placeholder="Search..." data-url="{{ route('product.search') }}"/>');
        });
      </script>
@endpush
