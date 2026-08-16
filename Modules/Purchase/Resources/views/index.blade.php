@extends('backend.partials.master')
@section('title',__('all_purchases'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('purchases') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('purchase.index') }}">{{ __('purchases') }}</a> </li>
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
                            @if(hasPermission('purchase_create'))
                                <a  href="{{ route('purchase.create') }}" class="btn btn-primary float-right  "  data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
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
                                        <th>{{ __('purchase_no') }}</th>   
                                        <th>{{ __('branch') }}</th>  
                                        <th>{{ __('supplier') }}</th>  
                                        <th>{{ __('purchase_status') }}</th>  
                                        <th>{{ __('payment_status') }}<br/></th>  
                                        <th>{{ __('total_purchase_cost') }}<br>(Inc.Tax)</th>  
                                        <th>{{ __('received_by') }}</th>   
                                        <th>{{ __('action') }}</th>
                                   
                                    </tr>
                                </thead>  
                                <tbody id="purchase_body">  
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
    <input type="hidden" id="get-purchase" data-url="{{ route('purchase.get.all') }}"/>
@endsection
@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>  
<script src="{{ static_asset('backend') }}/js/purchase/purchase_table.js" ></script>  
@endpush
