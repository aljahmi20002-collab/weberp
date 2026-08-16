@extends('backend.partials.master')
@section('title', 'Dashboard | Index')

<!-- Breadcrumb Start -->
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('dashboard') }}</h5>
        <ul class="breadcrumb">
            <li>
                <a href="{{ route('dashboard.index') }}">{{ __('home') }}</a>
            </li>
            <li>
                {{ __('dashboard') }}
            </li>
        </ul>
    </div>
@endsection()
<!-- Breadcrumb End -->
@section('maincontent')

    <div class="user-panel-wrapper dashboard">
        <!-- Dashboard Cards Start -->
        <div class="mb-4">
            <div class="row g-4 dashboard-count">
                <!-- Single Cards -->
                <div class="col-sm-6 col-xl-2 ">
                    <div class="dashboard-card">
                        <div class="card-header ">
                            <div class="info text-center">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="info text-center">
                                {{ __('total') }} {{ __('business') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="title text-info">{{ $total_business }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2  ">
                    <div class="dashboard-card">
                        <div class="card-header ">
                            <div class="info text-center">
                                <i class="fa fa-file-invoice-dollar"></i>
                            </div>
                            <div class="info text-center">
                                {{ __('total') }} {{ __('plan') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="title text-success">{{ $total_plan }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2  ">
                    <div class="dashboard-card">
                        <div class="card-header ">
                            <div class="info text-center">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="info text-center">
                                {{ __('active_subscription') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="title text-success">{{ $total_active_subscription }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Single Cards -->
                <div class="col-sm-6 col-xl-2 ">
                    <div class="dashboard-card">
                        <div class="card-header ">
                            <div class="info text-center">
                                <i class="fa fa-bars"></i>
                            </div>
                            <div class="info text-center">
                                {{ __('total') }} {{ __('role') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="title text-danger">{{ $total_roles }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Cards -->
                <div class="col-sm-6 col-xl-2 ">
                    <div class="dashboard-card">
                        <div class="card-header  ">
                            <div class="info text-center">
                                <i class="fas fa-th"></i>
                            </div>
                            <div class="info text-center">
                                {{ __('total') }} {{ __('module') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="title text-warning">{{ $total_modules }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Cards -->
                <div class="col-sm-6 col-xl-2  ">
                    <div class="dashboard-card">
                        <div class="card-header ">
                            <div class="info text-center">
                                <i class="fa fa-language"></i>
                            </div>
                            <div class="info text-center">
                                {{ __('total') }} {{ __('language') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="title text-success">{{ $total_language }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Dashboard Cards End -->
        <!-- Dashboard Chart Start -->
        <div class="row g-4">
            <!-- Chart One -->
            <div class="col-lg-12">
                <div class="dashboard--wrapper ">
                    <div class="todays-trend-chart dashboard--widget">
                        <div class="chart-area">
                            <div id="chart" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chart Two -->
            <div class="col-xl-6">
                <!-- Table -->
                <div class="card height100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="m-0">
                                {{ __('recent_business') }} {{ __('list') }}
                            </h5>
                            <div>
                                <a class="ml-10 pt-10 btn btn-sm btn-primary rounded-pill"
                                    href="{{ route('business.index') }}">{{ __('view') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('business_name') }}</th>
                                        <th>{{ __('logo') }}</th>
                                        <th>{{ __('start_date') }}</th>
                                        <th>{{ __('currency') }}</th>
                                        <th>{{ __('status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($recent_business as $business)
                                        <tr>
                                            <td data-title="#">{{ ++$i }}</td>
                                            <td data-title="{{ __('business_name') }}">{{ @$business->business_name }}
                                            </td>
                                            <td data-title="{{ __('logo') }}"><img src="{{ $business->logo_img }}"
                                                    width="50px" /></td>
                                            <td data-title="{{ __('start_date') }}">
                                                {{ @dateFormat($business->start_date) }}</td>
                                            <td data-title="{{ __('currency') }}"> {{ @$business->currency->currency }} (
                                                {{ @$business->currency->symbol }} )</td>
                                            <td data-title="{{ __('status') }}"> {!! @$business->my_status !!} </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Table -->
            </div>
            <div class="col-xl-6">
                <!-- Table -->
                <div class="card height100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="m-0">
                                {{ __('recent_plan') }} {{ __('list') }}
                            </h5>
                            <div>
                                <a class="ml-10 pt-10 btn btn-sm btn-primary rounded-pill"
                                    href="{{ route('plans.index') }}">{{ __('view') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('name') }}</th>
                                        <th>{{ __('user_count') }}</th>
                                        <th>{{ __('days_count') }}</th>
                                        <th>{{ __('price') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('modules') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($recent_plans as $plan)
                                        <tr>
                                            <td data-title="#">{{ ++$i }}</td>
                                            <td data-title="{{ __('name') }}">{{ @$plan->name }}</td>
                                            <td data-title="{{ __('user_count') }}">{{ @$plan->user_count }}</td>
                                            <td data-title="{{ __('days_count') }}"> {{ @$plan->days_count }} </td>
                                            <td data-title="{{ __('price') }}">{{ systemCurrency() }}
                                                {{ @$plan->price }} </td>

                                            <td data-title="{{ __('status') }}">
                                                {!! @$plan->my_status !!}
                                            </td>
                                            <td data-title="{{ __('modules') }}">
                                                @if (!blank($plan->modules))
                                                    <span
                                                        class="badge badge-pill badge-primary">{{ count($plan->modules) }}
                                                    </span>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Table -->
            </div>

        </div>
        <!-- Dashboard Chart End -->
    </div>
@endsection()
@push('scripts')
    <script type="text/javascript">
        var options = {
            series: [{
                name: '{{ __('business') }}',
                type: 'area',
                data: [
                    @foreach ($chart_dates as $date1)
                        {{ $business_count[$date1] }},
                    @endforeach
                ]
            }, {
                name: '{{ __('payment') }}',
                type: 'line',
                data: [
                    @foreach ($chart_dates as $date2)
                        {{ $chart_payment[$date2] }},
                    @endforeach
                ]
            }],
            chart: {
                height: 450,
                type: 'line',
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                type: 'solid',
                opacity: [0.051, 1],
            },
            title: {
                text: '{{ __('last_thirty_days_business_register') }}',
            },
            labels: [
                @foreach ($chart_dates as $date)
                    '{{ $date }}',
                @endforeach
            ],
            markers: {
                size: 0
            },
            yaxis: [{
                    title: {
                        text: '{{ __('business') }}',
                    },
                },

            ],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0);
                        }
                        return y;
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
