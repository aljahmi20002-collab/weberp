@extends('backend.partials.master')
@section('title', __('login_activity'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('login_activity') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="javascript:void(0)">{{ __('login_activity') }}</a> </li>
            <li> {{ __('list') }} </li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden">{{ __('login_activity') }} {{ __('list') }} </h4>
                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('user') }}</th>
                                        <th>{{ __('activity') }}</th>
                                        <th>{{ __('ip') }}</th>
                                        <th>{{ __('browser') }}</th>
                                        <th>{{ __('os') }}</th>
                                        <th>{{ __('device') }}</th>
                                        <th>{{ __('date_time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="even">
                                        <td valign="top" colspan="12" class="dataTables_empty">
                                            <div class="text-center">
                                                <img class="emptyTables" src="{{ settings('table_search_image') }}"
                                                    width="20%">
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
    <input type="hidden" id="get-login-activity" data-url="{{ route('login.activity.get.all') }}" />
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/login_activity/login_activity.js"></script>
@endpush
