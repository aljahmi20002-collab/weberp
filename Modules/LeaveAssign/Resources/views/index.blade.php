@extends('backend.partials.master')
@section('title',__('leave_assign'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ Date('Y') }}  {{ __('leave_assign') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('hrm') }}</a> </li>
            <li> <a href="#">{{ __('leave_assign') }}</a> </li>
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
                            @if(hasPermission('leave_assign_create'))
                                <a href="{{ route('hrm.leave.assign.create') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
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
                                        <th>{{ __('leave_type') }}</th>
                                        <th>{{ __('role') }}</th>
                                        <th>{{ __('days') }}</th>
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
    <input type="hidden" id="get-leave-assign" data-url="{{ route('hrm.leave.assign.get.all') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script src="{{ static_asset('backend') }}/js/hrm/leave_assign_table.js" ></script>
@endpush
