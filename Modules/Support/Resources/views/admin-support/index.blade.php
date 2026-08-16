@extends('backend.partials.master')
@section('title')
    {{ @$title }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0"> {{ @$title }} </h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ @$title }} </a> </li> 
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
                            @if(hasPermission('supports_create'))
                                <a href="{{ route('ticket.create') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="
                                    @if(isSuperadmin())
                                        {{ __('add') }}
                                    @else
                                        {{ __('submit_a_request') }}
                                    @endif
                                "
                                data-bs-placement="top">
                                    <i class="fa fa-plus"></i>
                                    @if(isSuperadmin())
                                        {{ __('add') }}
                                    @else
                                        {{ __('submit_a_request') }}
                                    @endif
                                </a>
                            @endif
                        </h4> 
                        <!-- Responsive Dashboard Table -->
                        <div class="">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th> 
                                        <th>{{ __('Details') }}</th>
                                        <th>{{ __('subject') }}</th> 
                                        <th>{{ __('priority') }}</th> 
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
    <input type="hidden" id="get-admin-support-ticket" data-url="{{ route('ticket.get.ticket') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script> 
    <script src="{{ static_asset('backend') }}/js/admin_support/ticket_table.js" ></script> 
@endpush
