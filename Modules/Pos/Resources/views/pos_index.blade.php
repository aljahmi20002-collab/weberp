@extends('backend.partials.master')
@section('title',__('pos_list'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('pos') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="#">{{ __('sell') }}</a> </li>
            <li> <a href="#">{{ __('pos') }}</a> </li>
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
                        <h4 class="card-title overflow-hidden"> 
                            @if(hasPermission('pos_create'))
                                <a  href="{{ route('pos.index') }}" class="btn btn-primary float-right  "  data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4>  
                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive category-table">
                            <table class="table table-striped table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th> 
                                        <th>{{ __('date') }}</th>  
                                        <th>{{ __('invoice_no') }}</th>  
                                        <th>{{ __('branch') }}</th>   
                                        <th>{{ __('customer_details') }}</th>   
                                        <th>{{ __('payment_status') }}</th>  
                                        <th>{{ __('shipping_status') }}</th>  
                                        <th>{{ __('total_sell_price') }}</th>  
                                        <th>{{ __('created_by') }}</th>   
                                        <th>{{ __('action') }}</th>  
                                    </tr>
                                </thead>  
                                <tbody  >   
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
    <input type="hidden" id="get-pos-list" data-url="{{ route('pos.get.all.pos') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>   
    <script src="{{ static_asset('backend') }}/js/pos/pos_table.js" ></script>   
@endpush
