@extends('backend.partials.master')
@section('title',__('user'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('user') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('user.index') }}">{{ __('user') }}</a> </li>
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
                        @if (hasPermission('user_create'))
                        <a href="{{ route('user.create') }}" class="btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('add') }}"
                        data-bs-placement="top">
                        <i class="fa fa-plus"></i> {{ __('add') }}
                        </a>
                        @endif
                    </h4>

                    <!-- Responsive Dashboard Table -->
                    <div class=" table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="border-bottom text-left">
                                    <th>#</th>
                                    <th>{{ __('details') }}</th>
                                    <th>{{ __('user_type') }}</th> 
                                    <th>{{ __('branch') }}</th> 
                                    <th>{{ __('role') }}</th>
                                    <th>{{ __('designation') }}</th>
                                    <th>{{ __('department') }}</th>
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
<input type="hidden" id="get-user" data-url="{{ route('user.get.all') }}"/>
@endsection

@push('scripts')
    <script  src="{{ static_asset('backend') }}/js/user/user.js"></script> 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script> 
    <script  src="{{ static_asset('backend') }}/js/user/user_table.js"></script> 
@endpush
