<div class="tab-pane " id="pills-attendance" role="tabpanel" aria-labelledby="pills-attendance-tab" tabindex="0">
  
    <div class="row ">  
        <div class="col-xl-4 mt-3"> 
            <div class="dashboard--widget"> 
                <h5 class="d-flex justify-content-between">{{ __('attendance') }}
                    <div class="dashboard d-inline-block">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle filter-date-btn attendanceDateBtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  >
                                {{ __('this_month') }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::today()->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}" data-report="{{ __('today') }}">{{ __('today') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::yesterday()->format('m/d/Y') }} - {{ \Carbon\Carbon::yesterday()->format('m/d/Y') }}" data-report="{{ __('yesterday') }}">{{ __('yesterday') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::today()->subDays(7)->format('m/d/Y') }} - {{ \Carbon\Carbon::today()->format('m/d/Y') }}" data-report="{{ __('last_7_days') }}">{{ __('last_7_days') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('m/d/Y') }}" data-report="{{ __('this_week') }}">{{ __('this_week') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->subWeek(1)->startOfWeek()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subWeek(1)->endOfWeek()->format('m/d/Y') }}" data-report="{{ __('last_week') }}">{{ __('last_week') }}</a> 
                              <a class="dropdown-item attendance-date active" href="#" data-date="{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}" data-report="{{ __('this_month') }}">{{ __('this_month') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->subMonth(1)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}" data-report="{{ __('last_month') }}">{{ __('last_month') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->subMonth(3)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}" data-report="{{ __('last_3_month') }}">{{ __('last_3_month') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->subMonth(6)->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subMonth(1)->endOfMonth()->format('m/d/Y') }}" data-report="{{ __('last_6_month') }}">{{ __('last_6_month') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfYear()->format('m/d/Y') }}" data-report="{{ __('this_year') }}">{{ __('this_year') }}</a> 
                              <a class="dropdown-item attendance-date" href="#" data-date="{{ \Carbon\Carbon::now()->subYear(1)->startOfYear()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->subYear(1)->endOfYear()->format('m/d/Y') }}" data-report="{{ __('last_year') }}">{{ __('last_year') }}</a> 
                            </div>
                          </div> 
                          <input type="hidden" class="form-control form--control" readonly id="user_date" data-url="{{ route('user.attendance.total') }}" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}"/>

                    </div>
                </h5>

                <div class="chart-area">
                    <div id="attendance_pie_chart" class="chart"></div>
                </div> 
            </div> 
        </div> 
    
        <div class="col-xl-8">
            <div class="row height100"> 
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100"> 
                        <div class="d-flex align-items-center height100">
                            <div> 
                            <i class="fa fa-star fontsize35px mr-20 text-warning"></i>
                            </div>
                            <div class="line-height-2">
                                <span>{{ __('total_holiday') }}</span> <br/>
                                <span class="fontsize20px" id="total_holiday"> 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100"> 
                        <div class="d-flex align-items-center height100">
                            <div>  
                            <i class="fa fa-right-from-bracket fontsize35px mr-20 text-danger"></i>
                            </div>
                            <div class="line-height-2">
                                <span>{{ __('total_leave') }}</span> <br/>
                                <span class="fontsize20px" id="total_leave_days">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100">
                        <div class="d-flex align-items-center height100">
                            <div  >   
                            <i class="fa fa-close fontsize35px mr-20 text-primary"></i>
                            </div>
                            <div class="line-height-2">
                                <span>{{ __('total_absent') }}</span>  <br/>
                                <span class="fontsize20px" id="total_absent"> 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-3">
                    <div class="dashboard--widget height100 "> 
                        <div class="d-flex align-items-center height100">
                            <div  >
                            <i class="fa fa-check fontsize35px mr-20 text-success"></i>
                            </div>
                            <div class="line-height-2">
                                <span>{{ __('total_present') }} <i class="fa fa-info-circle " data-toggle="tooltip"  title="Present + Not checkout"></i></span> <br/>
                                <span class="fontsize20px" id="total_present"> 0</span>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div>  
</div> 
@push('scripts') 
<script src="{{static_asset('backend')}}/js/daterangepicker.min.js"></script> 
<script src="{{static_asset('backend')}}/js/daterangepicker/daterangepicker.js"></script>  
    @include('backend.user.view-content.attendance_chart_js')
@endpush    