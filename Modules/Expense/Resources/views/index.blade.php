@extends('backend.partials.master')
@section('title',__('expense'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('expense') }}</h5>
        <ul class="breadcrumb">
            <li><a href="#">{{ __('accounts') }} </a> </li>
            <li><a href="#">{{ __('expense') }}</a> </li> 
            <li class="active">{{ __('list') }} </li>
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
                            @if(hasPermission('expense_create'))
                                <a href="{{ route('accounts.expense.create') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4> 
                        <!-- Responsive Dashboard Table -->
                        <div class=" table-responsive">
                            <table class="table table-striped table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('from_account') }}</th>
                                        <th>{{ __('to_branch') }}</th> 
                                        <th>{{ __('to_account') }}</th>
                                        <th>{{ __('amount') }}</th>
                                        <th>{{ __('note') }}</th> 
                                        <th>{{ __('document') }}</th>
                                        <th>{{ __('created_by') }}</th> 
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
    <input type="hidden" id="get-expenses" data-url="{{ route('accounts.expense.get.all') }}"/>
@endsection 
@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
<script src="{{ static_asset('backend') }}/js/expense/expense_table.js" ></script>
@endpush
