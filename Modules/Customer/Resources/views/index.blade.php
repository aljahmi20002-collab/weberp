@extends('backend.partials.master')
@section('title',__('customers'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('customers') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('customers.index') }}">{{ __('customer') }}</a> </li>
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
                            @if(hasPermission('customer_create'))
                                <a href="{{ route('customers.create') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                    data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4> 
                        <!-- Responsive Dashboard Table -->
                        <div class=" table-responsive plan-table">
                            <table class="table table-striped table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>  
                                        <th>{{ __('customer') }}</th>   
                                        <th>{{ __('opening_balance') }}</th>
                                        <th>{{ __('balance') }}</th>
                                        <th>{{ __('status') }}</th> 
                                        <th>{{ __('action') }}</th> 
                                    </tr>
                                </thead>
                                <tbody> 
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
<input type="hidden" id="get-customers" data-url="{{ route('customers.get.all') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script src="{{ static_asset('backend') }}/js/customer/customer_table.js" ></script>
@endpush
