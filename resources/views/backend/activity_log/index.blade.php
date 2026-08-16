@extends('backend.partials.master')
@section('title', 'Activity logs')

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('activity_logs') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="javascript:void(0)">{{ __('activity_logs') }}</a> </li>
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
                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('log_name') }}</th>
                                        <th>{{ __('event') }}</th>
                                        <th>{{ __('subject_type') }}</th>
                                        <th>{{ __('description') }}</th>
                                        <th>{{ __('view') }}</th>
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

    <input type="hidden" id="get-activity-logs" data-url="{{ route('activity.logs.get.all') }}" />
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/activity_logs/logs.js"></script>
@endpush
