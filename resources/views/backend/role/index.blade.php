@extends('backend.partials.master')
@section('title','Roles')

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('role') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('role.index') }}">{{ __('role') }}</a> </li>
            <li>  {{ __('list') }} </li>
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
                        @if(hasPermission('role_create'))
                            <a href="{{ route('role.create') }}" class="btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('add') }}"
                            data-bs-placement="top">
                                <i class="fa fa-plus"></i> {{ __('add') }}
                            </a>
                        @endif
                    </h4>
 
                    <!-- Responsive Dashboard Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="border-bottom">
                                    <th>#</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('slug') }}</th>
                                    <th>{{ __('permissions') }}</th>
                                    <th>{{ __('status') }}</th> 
                                    <th>{{ __('action') }}</th> 
                                </tr>
                            </thead>
                            <tbody>
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
<input type="hidden" id="get-role" data-url="{{ route('role.all') }}"/>
@endsection
@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
<script src="{{ static_asset('backend') }}/js/role/role_table.js" ></script>
@endpush
