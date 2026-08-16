<!DOCTYPE html>
<html lang="en"    >

    <head id="head">
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ __('attendance_report') }}</title>   
         <style>
            body{
                font-size: 12px;
                margin: 0px;
                padding:0px;
            }
            table{
                width: 100%;
            }
             
            .col-8{
                width: 65%;
            }
            .col-4{
                width: 35%;
            }
            svg{
                width: 15px;
            }
            svg.hourglass{
                width: 10px!important;
            }
            table{
                border-color:rgb(227 227 227 / 32%) !important;
            }
            table td{
                padding:5px!important;
            }
 
            table th{ 
                text-align: left;
                border-bottom:1px solid rgba(73, 73, 73, 0.226);
            }
            table td{ 
                text-align: left;
                border-bottom:1px solid rgba(73, 73, 73, 0.226);
            }
            
             
         </style>
    </head>
    <body>
        <div class="">  
            <table class="row mb-0 align-items-center">
                <tr>
                    <td class="col-8 mb-2 "> 
                        <p class="text-left"><b class="cmr-5">{{ __('business_name') }}:</b> {{ @$employee->business_info->business_name }}</p>
                        <p class="text-left"><b class="cmr-5">{{ __('name') }}:</b> {{ @$employee->name }}</p>
                        <p class="text-left"><b class="cmr-5">{{ __('email') }}:</b> {{ @$employee->email }}</p>
                        <p class="text-left"><b class="cmr-5">{{ __('phone') }}:</b> {{ @$employee->phone }}</p>
                    </td> 
                    <td class="col-4"> 
                        <table class="table border custom-table"  cellpadding="0" cellspacing="0" style="margin: 2px" >
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
                    </td>
                </tr>
            </table> 
           <!-- Responsive Dashboard Table -->
            <div class="table-responsive table-responsive">
                <table class="table table-striped table-hover" cellpading="0" cellspacing="0">
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
 
    </body>
</html>
