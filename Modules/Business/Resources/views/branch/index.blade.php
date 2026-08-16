@extends('backend.partials.master')
@section('title',__('business_branch_list'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('branch') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('business.index') }}">{{ __('business') }}</a> </li>
            <li> <a href="#">{{ __('branch') }}</a> </li>
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
                    <div class="row mb-5">
                        <div class="col-12 ">
                            <p class="text-center"><img src="{{ @$business->logo_img }}" width="200"/></p>
                            <p class="text-center"><b class="cmr-5">{{ __('business_name') }}:</b> {{ @$business->business_name }}</p>
                        </div>
                    </div>
                    <h4 class="card-title overflow-hidden"> 
                        @if(hasPermission('business_branch_create'))
                            <a href="{{ route('business.branch.create',$business_id) }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                            data-bs-placement="top">
                                <i class="fa fa-plus"></i> {{ __('add') }}
                            </a>
                        @endif
                    </h4>
                    <!-- Responsive Dashboard Table -->
                    <div class=" table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="border-bottom">
                                    <th>#</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('website') }}</th>
                                    <th>{{ __('phone') }}</th>
                                    <th>{{ __('country') }}</th>
                                    <th>{{ __('state') }}</th>
                                    <th>{{ __('city') }}</th>
                                    <th>{{ __('zip_code') }}</th>
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
<input type="hidden" id="get-business-branch" data-url="{{ route('business.branch.get.all.branch',$business_id) }}"/>

@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script src="{{ static_asset('backend') }}/js/business/business_branch_table.js" ></script>
@endpush
