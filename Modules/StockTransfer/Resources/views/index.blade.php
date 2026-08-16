@extends('backend.partials.master')
@section('title',__('all_stock_transfer'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('stock_transfer') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('stock.transfer.index') }}">{{ __('stock_transfer') }}</a> </li>
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
                            @if(hasPermission('stock_transfer_create'))
                                <a  href="{{ route('stock.transfer.create') }}" class="btn btn-primary float-right  "  data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
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
                                        <th>{{ __('transfer_no') }}</th>   
                                        <th>{{ __('from_branch') }}</th>    
                                        <th>{{ __('to_branch') }}</th>    
                                        <th>{{ __('total_amount') }}<br></th>  
                                        <th>{{ __('status') }}<br/></th>  
                                        <th>{{ __('transfered_by') }}</th>   
                                        <th>{{ __('status_update') }}</th>   
                                        <th>{{ __('action') }}</th> 
                                    </tr>
                                </thead>  
                                <tbody  >
                                    <tr class="even">
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
    <input type="hidden" id="get-stock-transfer" data-url="{{ route('stock.transfer.get.all') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>  
    <script src="{{ static_asset('backend') }}/js/stock_transfer/stock_transfer_table.js" ></script>  
@endpush
