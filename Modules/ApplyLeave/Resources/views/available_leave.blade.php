@extends('backend.partials.master')
@section('title',__('available_leave'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ Date('Y') }} {{ __('available_leave') }}</h5>
        <ul class="breadcrumb">
            <li >  <a href="#">{{ __('hrm') }}</a></li>
            <li> <a href="#">{{ __('available_leave') }}</a> </li>
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
                        

                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('leave_type') }}</th>
                                        <th>{{ __('role') }}</th>
                                        <th>{{ __('total_days') }}</th>
                                        <th>{{ __('remaining_days') }}</th> 
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
<input type="hidden" id="get-available-leave" data-url="{{ route('hrm.available.leave.get.all') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script src="{{ static_asset('backend') }}/js/hrm/available_leave_table.js" ></script>
@endpush
