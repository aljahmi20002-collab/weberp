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

    <div class="user-panel-wrapper business-dashboard">
        <!-- Dashboard Cards Start -->
        <div class="mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <select class="form-control select2" id="branch_id">
                                <option value="">{{ __('select') }} {{ __('branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            @if ($attendance && $attendance->status == \Modules\Attendance\Enums\AttendanceStatus::CHECK_OUT)
                                <button type="button" class="btn btn-success modalBtn" data-bs-toggle="modal"
                                    data-bs-target="#dynamic-modal" data-title="{{ __('details') }}"
                                    data-url="{{ route('hrm.attendance.details.modal', ['employee_id' => Auth::user()->id, 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}">{{ __('checked_out') }}</button>
                            @elseif($attendance && $attendance->status == \Modules\Attendance\Enums\AttendanceStatus::PENDING)
                                <button type="button" class="btn btn-danger modalBtn" data-bs-toggle="modal"
                                    data-bs-target="#dynamic-modal" data-title="{{ __('check_out') }}"
                                    data-url="{{ route('hrm.attendance.checkout.modal', ['employee_id' => Auth::user()->id, 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}">{{ __('checkout') }}</button>
                            @else
                                <button type="button" class="btn btn-success modalBtn" data-bs-toggle="modal"
                                    data-bs-target="#dynamic-modal" data-title="{{ __('check_in') }}"
                                    data-url="{{ route('hrm.attendance.create.modal', ['employee_id' => Auth::user()->id, 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}">{{ __('checkin') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">

                    <h5 class="my-3 d-flex justify-content-between align-items-center">
                        <div>{{ __('sales') }}</div>
                        <div class="dashboard">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle filter-date-btn salesDateBtn"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-filter text-color-black"></i> {{ __('today') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item sales-date active" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('today') }}">{{ __('today') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::yesterday()->format('m/d/Y') }} - {{ \Carbon\Carbon::yesterday()->format('m/d/Y') }}"
                                        data-report="{{ __('yesterday') }}">{{ __('yesterday') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->subDays(7)->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('last_7_days') }}">{{ __('last_7_days') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('this_week') }}">{{ __('this_week') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subWeek(1)->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subWeek(1)->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('last_week') }}">{{ __('last_week') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('this_month') }}">{{ __('this_month') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(1)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_month') }}">{{ __('last_month') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(3)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_3_month') }}">{{ __('last_3_month') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(6)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_6_month') }}">{{ __('last_6_month') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('this_year') }}">{{ __('this_year') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('last_year') }}">{{ __('last_year') }}</a>
                                    <a class="dropdown-item sales-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-lifetime="lifetime"
                                        data-report="{{ __('lifetime') }}">{{ __('lifetime') }}</a>
                                </div>
                            </div>
                            <input type="hidden" class="form-control form--control date_range_picker" readonly
                                id="sales_date" data-url="{{ route('dashboard.sales.total') }}" />
                        </div>
                    </h5>
                    <div class="row g-4">
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('sales') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-info">{{ businessCurrency(business_id()) }}<span
                                                class="total_sales">0</span></h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('payments') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-success ">{{ businessCurrency(business_id()) }}<span
                                                class="total_sale_payments">0</span></h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('due') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-warning ">{{ businessCurrency(business_id()) }}<span
                                                class="total_sale_due">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('net') }} <i class="fa fa-info-circle " data-toggle="tooltip"
                                            title="Total = ( Total Sales - Total due ) - Total expense"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-success ">{{ businessCurrency(business_id()) }}<span
                                                class="total_sale_net">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <h5 class="my-3 d-flex justify-content-between align-items-center">
                        <div>{{ __('pos') }}</div>
                        <div class="dashboard">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle filter-date-btn posDateBtn"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-filter text-color-black"></i> {{ __('today') }}
                                </button>
                                <div class="dropdown-menu pos-dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item pos-date active" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('today') }}">{{ __('today') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::yesterday()->format('m/d/Y') }} - {{ \Carbon\Carbon::yesterday()->format('m/d/Y') }}"
                                        data-report="{{ __('yesterday') }}">{{ __('yesterday') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->subDays(7)->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('last_7_days') }}">{{ __('last_7_days') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('this_week') }}">{{ __('this_week') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subWeek(1)->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subWeek(1)->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('last_week') }}">{{ __('last_week') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('this_month') }}">{{ __('this_month') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(1)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_month') }}">{{ __('last_month') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(3)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_3_month') }}">{{ __('last_3_month') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(6)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_6_month') }}">{{ __('last_6_month') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('this_year') }}">{{ __('this_year') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('last_year') }}">{{ __('last_year') }}</a>
                                    <a class="dropdown-item pos-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-lifetime="lifetime"
                                        data-report="{{ __('lifetime') }}">{{ __('lifetime') }}</a>
                                </div>
                                <input type="hidden" class="form-control form--control date_range_picker" id="pos_date"
                                    readonly data-url="{{ route('dashboard.sales.pos.total') }}" />
                            </div>
                        </div>
                    </h5>
                    <div class="row g-4">
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('pos') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center font-weight-bold">
                                        <h2 class="title text-info">{{ businessCurrency(business_id()) }}<span
                                                class="total_pos">0</span></h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('payments') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-success ">{{ businessCurrency(business_id()) }}<span
                                                class="total_pos_payments">0</span></h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('due') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-warning ">{{ businessCurrency(business_id()) }}<span
                                                class="total_pos_due">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total_expense') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-danger ">{{ businessCurrency(business_id()) }}<span
                                                class="total_expense">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <h5 class="my-3 d-flex justify-content-between align-items-center">
                        <div>{{ __('purchase') }}</div>
                        <div class="dashboard">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle filter-date-btn purchaseDateBtn"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-filter text-color-black"></i> {{ __('today') }}
                                </button>
                                <div class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item purchase-date active" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('today') }}">{{ __('today') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::yesterday()->format('m/d/Y') }} - {{ \Carbon\Carbon::yesterday()->format('m/d/Y') }}"
                                        data-report="{{ __('yesterday') }}">{{ __('yesterday') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->subDays(7)->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('last_7_days') }}">{{ __('last_7_days') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('this_week') }}">{{ __('this_week') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subWeek(1)->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subWeek(1)->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('last_week') }}">{{ __('last_week') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('this_month') }}">{{ __('this_month') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(1)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_month') }}">{{ __('last_month') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(3)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_3_month') }}">{{ __('last_3_month') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(6)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_6_month') }}">{{ __('last_6_month') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('this_year') }}">{{ __('this_year') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('last_year') }}">{{ __('last_year') }}</a>
                                    <a class="dropdown-item purchase-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-lifetime="lifetime"
                                        data-report="{{ __('lifetime') }}">{{ __('lifetime') }}</a>
                                </div>
                            </div>

                            <input type="hidden" class="form-control form--control date_range_picker" readonly
                                id="purchase_date" data-url="{{ route('dashboard.sales.purchase.total') }}" />
                        </div>
                    </h5>
                    <div class="row g-4">
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('quantity') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-info"><span class="total_purchase_items">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('purchase') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-info">{{ businessCurrency(business_id()) }}<span
                                                class="total_purchase">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('payments') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-success ">{{ businessCurrency(business_id()) }}<span
                                                class="total_purchase_payments">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('due') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-warning ">{{ businessCurrency(business_id()) }}<span
                                                class="total_purchase_due">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-6">


                    <h5 class="my-3 d-flex justify-content-between align-items-center">
                        <div>{{ __('purchase_return') }}</div>
                        <div class="dashboard">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle filter-date-btn purchaseReturnDateBtn"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-filter text-color-black"></i> {{ __('today') }}
                                </button>
                                <div class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item purchase-return-date active" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('today') }}">{{ __('today') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::yesterday()->format('m/d/Y') }} - {{ \Carbon\Carbon::yesterday()->format('m/d/Y') }}"
                                        data-report="{{ __('yesterday') }}">{{ __('yesterday') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::today()->subDays(7)->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}"
                                        data-report="{{ __('last_7_days') }}">{{ __('last_7_days') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('this_week') }}">{{ __('this_week') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subWeek(1)->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subWeek(1)->endOfWeek()->format('m/d/Y') }}"
                                        data-report="{{ __('last_week') }}">{{ __('last_week') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('this_month') }}">{{ __('this_month') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(1)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_month') }}">{{ __('last_month') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(3)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_3_month') }}">{{ __('last_3_month') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subMonth(6)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}"
                                        data-report="{{ __('last_6_month') }}">{{ __('last_6_month') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('this_year') }}">{{ __('this_year') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-report="{{ __('last_year') }}">{{ __('last_year') }}</a>
                                    <a class="dropdown-item purchase-return-date" href="#"
                                        data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}"
                                        data-lifetime="lifetime"
                                        data-report="{{ __('lifetime') }}">{{ __('lifetime') }}</a>
                                </div>
                            </div>
                            <input type="hidden" class="form-control form--control date_range_picker" readonly
                                id="purchase_return_date"
                                data-url="{{ route('dashboard.sales.purchase.return.total') }}" />
                        </div>
                    </h5>
                    <div class="row g-4">
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('quantity') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-info"><span class="total_purchase_return_items">0</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header  ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('purchase') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-info">{{ businessCurrency(business_id()) }}<span
                                                class="total_purchase_return">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('payments') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-success ">{{ businessCurrency(business_id()) }}<span
                                                class="total_purchase_return_payments">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Cards -->
                        <div class="col-sm-6 col-xl-3 ">
                            <div class="dashboard-card">
                                <div class="card-header ">
                                    <div class="info text-center font-weight-bold">
                                        {{ __('total') }} {{ __('due') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="title text-warning ">{{ businessCurrency(business_id()) }}<span
                                                class="total_purchase_return_due">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Dashboard Cards End -->

        <!-- Dashboard Chart Start -->
        <div class="row g-4">
            <!-- sale Chart One -->
            <div class="col-xl-6">
                <div class="dashboard--wrapper ">
                    <div class="todays-trend-chart dashboard--widget">
                        <div class="chart-area">
                            <div id="sales_chart" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chart one -->
            <!-- pos Chart two -->
            <div class="col-xl-6">
                <div class="dashboard--wrapper ">
                    <div class="todays-trend-chart dashboard--widget">
                        <div class="chart-area">
                            <div id="pos_chart" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chart Two -->

            <div class="col-xl-6">
                <!--sale Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0">
                                {{ __('recent_sales') }}
                            </h5>
                            <div class="d-flex  align-items-center">
                                <div>
                                    <select class="form-control select2 " id="recent_sales_branch_id">
                                        <option value="">{{ __('select') }} {{ __('branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <a class="ml-10 pt-10 btn btn-sm btn-primary rounded-pill"
                                        href="{{ route('sale.index') }}">{{ __('view') }}</a>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table align-middle table-centered table-vertical table-nowrap mb-0 table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('invoice_no') }}</th>
                                        <th>{{ __('branch') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('total_selling_price') }}</th>
                                        <th>{{ __('total_payments') }}</th>
                                        <th>{{ __('total_due') }}</th>

                                    </tr>
                                </thead>
                                <tbody id="recent_sales" data-url="{{ route('dashboard.sales.recent.sales') }}">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ __('sale_was_not_found') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Table -->
            </div>

            <div class="col-xl-6">
                <!--pos Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0">
                                {{ __('recent_pos') }}
                            </h5>
                            <div class="d-flex align-items-center">
                                <div>
                                    <select class="form-control select2" id="recent_pos_branch_id">
                                        <option value="">{{ __('select') }} {{ __('branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <a class="ml-10 pt-10 btn btn-sm btn-primary rounded-pill"
                                        href="{{ route('pos.list') }}">{{ __('view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table align-middle table-centered table-vertical table-nowrap mb-0 table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('invoice_no') }}</th>
                                        <th>{{ __('branch') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('total_selling_price') }}</th>
                                        <th>{{ __('total_payments') }}</th>
                                        <th>{{ __('total_due') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="recent_pos" data-url="{{ route('dashboard.sales.recent.pos') }}">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ __('pos_was_not_found') }}
                                        </td>
                                    </tr>
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
    <input type="hidden" id="pos_date" data-url="{{ route('dashboard.sales.pos.total') }}" />
@endsection
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ static_asset('backend') }}/css/datepicker.css" />
    <style>
        .select2-container.select2-container--default.select2-container--open,
        span.select2.select2-container.select2-container--default.select2-container--focus,
        .select2.select2-container.select2-container--default {
            width: 200px !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/daterangepicker.min.js"></script>
    <script src="{{ static_asset('backend') }}/js/daterangepicker/daterangepicker.js"></script>
    <script src="{{ static_asset('backend') }}/js/reports/daterangepicker.js"></script>
    <script src="{{ static_asset('backend') }}/js/businessdashboard/business_dashboard.js"></script>
    @include('backend.business_dashboard.thirty_days_sales_chart')
    @include('pos::business_dashboard.thirty_days_pos_chart')
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js"></script>
    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endpush
