@extends('backend.partials.master')
@section('title',__('all_return_purchases'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('purchase_return') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('purchase.index') }}">{{ __('purchase_return') }}</a> </li>
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
                            @if(hasPermission('purchase_return_create'))
                                <a  href="{{ route('purchase.return.create') }}" class="btn btn-primary float-right  "  data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
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
                                        <th>{{ __('return_no') }}</th>   
                                        <th>{{ __('branch') }}</th>  
                                        <th>{{ __('supplier') }}</th>   
                                        <th>{{ __('payment_status') }}<br/></th>  
                                        <th>{{ __('total_return_price') }}<br>(Inc.Tax)</th>  
                                        <th>{{ __('returned_by') }}</th>   
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
    <input type="hidden" id="get-purchase-return" data-url="{{ route('purchase.return.get.all') }}"/>
@endsection
@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>  
<script src="{{ static_asset('backend') }}/js/purchase/purchase_return/purchase_return_table.js" ></script>  
@endpush
