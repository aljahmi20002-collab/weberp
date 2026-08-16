<!DOCTYPE html>
<html lang="en" dir={{ languageDirection(app()->getLocale()) }}  >

    <head id="head">
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ __('attendance_report') }}</title>
        <link rel="stylesheet" href="{{static_asset('backend/assets')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{static_asset('backend/assets')}}/css/all.min.css">
        <link rel="stylesheet" href="{{static_asset('backend')}}/assets/css/flag-icon-css/flag-icon.min.css">
        <link rel="stylesheet" href="{{static_asset('backend')}}/css/custom.css">
        <link rel="stylesheet" href="{{static_asset('backend')}}/css/attendanceprint.css">
        <link rel="shortcut icon" href="{{ settings('favicon')}}" type="image/x-icon" /> 
        <style>
            @media print {
                @page  {
                    margin: 1cm;
                }
                body{
                    background: white!important;
                }
                .action{
                    display: none!important;
                }
            }
           
        </style>
    </head>
    <body>
        <div class=" "> 
            <h5 class=" overflow-hidden text-end  " >
                <div  class='text-center'> 
                    <button type="button"  onclick="window.close()" class="btn btn-sm btn-danger float-right action m-2 print-btn"><i class="fa fa-window-close"></i></button>
                    <button type="button"  onclick="window.print()" class="btn btn-sm btn-primary float-right action m-2 print-btn"><i class="fa fa-print"></i></button>
            </div>
            </h5>
            <div class="row mb-0 align-items-center">
                <div class="col-8 mb-2 ">
                    <p class="text-left"><img src="{{  @$employee->business_info->logo_img  }}" width="50"/></p>
                    <p class="text-left"><b class="cmr-5">{{ __('business_name') }}:</b> {{ @$employee->business_info->business_name }}</p>
                    <p class="text-left"><b class="cmr-5">{{ __('name') }}:</b> {{ @$employee->name }}</p>
                    <p class="text-left"><b class="cmr-5">{{ __('email') }}:</b> {{ @$employee->email }}</p>
                    <p class="text-left"><b class="cmr-5">{{ __('phone') }}:</b> {{ @$employee->phone }}</p>
                </div> 
                <div class="col-4">
                    <table class="table border custom-table">
                        <tr> 
                            <td>{{ __('total_day') }}</td>
                            <td>{{  @$reportTotal['total_days'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('total_holiday') }}</td>
                            <td>{{  @$reportTotal['total_holidays'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('total_leave_day') }}</td>
                            <td>{{  @$reportTotal['total_leave_days'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('total_present') }}</td>
                            <td>{{  @$reportTotal['total_present'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('total_pending') }}</td>
                            <td>{{  @$reportTotal['total_pending'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('total_absent') }}</td>
                            <td>{{  @$reportTotal['total_absent'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="py-2">
                <b>Note:</b>
                <span class="m-2"><i class="fa fa-star text-warning "></i> <i class="fa fa-arrow-right"></i> {{ __('holiday') }}  </span>|
                <span class="m-2"><i class="fa fa-check text-success "></i> <i class="fa fa-arrow-right"></i> {{ __('present') }} </span>|
                <span class="m-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg> <i class="fa fa-arrow-right"></i> {{ __('absent') }} </span> | 
                <span class="m-2"><i class="fa fa-hourglass-start text-primary"></i> <i class="fa fa-arrow-right"></i> {{ __('not_check_out') }}</span> | 
                <span class="m-2 leave-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                  </svg> <i class="fa fa-arrow-right"></i> {{ __('leave') }}</span>
            </div>
           <!-- Responsive Dashboard Table -->
            <div class="table-responsive table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="border-bottom"> 
                            <th>{{ __('date') }}</th>
                            <th>{{ __('check_in') }}</th>
                            <th>{{ __('check_out') }}</th>  
                            <th>{{ __('status') }}</th>  
                            <th>{{ __('stay_time') }}</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($reportTotal['request_dates'] as $date) 
                            <tr> 
                                <td data-title="{{ __('date') }}">{{\Carbon\Carbon::parse($date)->format('d-m-Y')}}</td>
                               
                                <td data-title="{{ __('check_in') }}"> 
                                    @if(ReportAttendanceFind($employee->id,$date) && ReportAttendanceFind($employee->id,$date)->check_in)
                                        {{\Carbon\Carbon::parse(ReportAttendanceFind($employee->id,$date)->check_in)->format('h:i A') }}
                                    @endif 
                                </td>
                                <td data-title="{{ __('check_out') }}">
                                    @if(ReportAttendanceFind($employee->id,$date) && ReportAttendanceFind($employee->id,$date)->check_out)
                                    {{\Carbon\Carbon::parse(ReportAttendanceFind($employee->id,$date)->check_out)->format('h:i A') }}
                                    @endif
                                </td>
                                <td data-title="{{ __('status') }}"> {!! @attendanceStatus($date,$request) !!}</td>
                                <td data-title="{{ __('stay_time') }}">{{@ReportAttendanceFind($employee->id,$date)->staytime }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- Responsive Dashboard Table --> 
        </div>

    <script type="text/javascript">
            window.print()
    </script>
    </body>
</html>
