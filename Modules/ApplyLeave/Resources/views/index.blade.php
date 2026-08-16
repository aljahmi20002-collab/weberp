@extends('backend.partials.master')
@section('title',__('apply_leave'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ Date('Y') }} {{ __('apply_leave') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('hrm') }} </a></li>
            <li> <a href="#">{{ __('apply_leave') }}</a> </li>
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
                            @if(hasPermission('apply_leave_create'))
                                <a href="{{ route('hrm.apply.leave.create') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4>

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link   @if(!$request->pending && !$request->approved && !$request->rejected) active @endif" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="true">{{ __('all') }}</button>
                                <button class="nav-link @if($request->pending) active @endif" id="nav-pending-tab" data-bs-toggle="tab" data-bs-target="#nav-pending" type="button" role="tab" aria-controls="nav-pending" aria-selected="false">{{ __('pending') }}</button>
                                <button class="nav-link  @if($request->approved) active @endif" id="nav-approved-tab" data-bs-toggle="tab" data-bs-target="#nav-approved" type="button" role="tab" aria-controls="nav-approved" aria-selected="false">{{ __('approved') }}</button>
                                <button class="nav-link  @if($request->rejected) active @endif" id="nav-rejected-tab" data-bs-toggle="tab" data-bs-target="#nav-rejected" type="button" role="tab" aria-controls="nav-rejected" aria-selected="false" rejected>{{ __('rejected') }}</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade @if(!$request->pending && !$request->approved && !$request->rejected) show active @endif" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab" tabindex="0">
                                <!-- Responsive Dashboard Table -->
                                <div class="  table-responsive">
                                    <table class="table all-applied-leave table-striped table-hover">
                                        <thead>
                                            <tr class="border-bottom">
                                                <th>#</th>
                                                <th>{{ __('applicant') }}</th>
                                                <th>{{ __('leave_type') }}</th>
                                                <th>{{ __('leave_from') }}</th>
                                                <th>{{ __('leave_to') }}</th>
                                                <th>{{ __('file') }}</th>
                                                <th>{{ __('reason') }}</th>
                                                <th>{{ __('status') }}</th>
                                                <th>{{ __('submited') }}</th> 
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
                            <div class="tab-pane fade @if($request->pending) show active @endif" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab" tabindex="0">
                                @include('applyleave::applied_leave.pending')
                            </div>
                            <div class="tab-pane fade @if($request->approved) show active @endif" id="nav-approved" role="tabpanel" aria-labelledby="nav-approved-tab" tabindex="0">
                                @include('applyleave::applied_leave.approved')
                            </div>
                            <div class="tab-pane fade @if($request->rejected) show active @endif" id="nav-rejected" role="tabpanel" aria-labelledby="nav-rejected-tab" tabindex="0">
                                @include('applyleave::applied_leave.rejected')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" id="get-all-applied-leave" data-url="{{ route('hrm.apply.leave.get.all.applied.leave') }}"/>
<input type="hidden" id="get-pending-applied-leave" data-url="{{ route('hrm.apply.leave.get.pending.applied.leave') }}"/>
<input type="hidden" id="get-approved-applied-leave" data-url="{{ route('hrm.apply.leave.get.approved.applied.leave') }}"/>
<input type="hidden" id="get-rejected-applied-leave" data-url="{{ route('hrm.apply.leave.get.rejected.applied.leave') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script src="{{ static_asset('backend') }}/js/hrm/apply_leave_table.js" ></script>
@endpush
