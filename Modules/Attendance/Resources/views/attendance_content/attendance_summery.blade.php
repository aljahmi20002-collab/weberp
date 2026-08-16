<div class="tab-pane fade show active" id="nav-summery" role="tabpanel" aria-labelledby="nav-summery-tab" tabindex="0">
    <!-- Responsive Dashboard Table -->
    <div class="table-responsive table-responsive">
        <div class="text-center">
                <b>{{ $data['monthyear'] }}</b>
        </div>
        <div class="p-2">
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
        <table class="table table-striped table-hover text-left">
            <thead>
                <tr class="border-bottom">
                    <th>#</th>
                    <th>{{ __('employee_name') }}</th>
                    @foreach ($data['full_month_dates'] as $date)
                        <th>{{ @dateDay($date)['d'] }} <br/> {{ @dateDay($date)['D'] }}</th>
                    @endforeach
                    <th>{{ __('total') }}</th>
                   
                </tr>
            </thead>
            <tbody>
                @php
                    $i=0;
                @endphp
                @foreach ($users as $user)

                    <tr>
                        <td data-title="#">{{ ++$i }}</td>
                        <td data-title="{{ __('employee_name') }}">
                            <div class="row text-left">
                                <div class="col-lg-3">
                                    <img src="{{ @$user->image }}" width="60" class="rounded-circle"/>
                                </div>
                                <div class="col-lg-9">
                                    <strong>{{@$user->name}}</strong>
                                    <p> {{ @$user->usertypes }}</p>
                                </div>
                            </div> 
                        </td>
                        @foreach ($data['full_month_dates'] as $date)
                         
                            <td data-title="{{ @dateDay($date)['d'] }} {{ @dateDay($date)['D'] }}day">
                                
                                 @if(\Carbon\Carbon::parse($date)->format('d') <= date('d'))
                                    {!! @dayAttendance($user->id,$date)  !!}
                                 @else
                                 -
                                 @endif
                            </td>
                        @endforeach
                        <td data-title="{{ __('total') }}"> 
                            <span class="text-primary">{{ totalPresent($user->id,$data) }}</span>/ {{ @$data['total_month_days'] }} 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Responsive Dashboard Table -->
    <!-- Pagination -->
    <div class="d-flex flex-row-reverse align-items-center pagination-content">
        <span class="paginate">{{ $users->links() }}</span>
        <p class="p-2 small paginate">
            {!! __('Showing') !!}
            <span class="font-medium">{{ $users->firstItem() }}</span>
            {!! __('to') !!}
            <span class="font-medium">{{ $users->lastItem() }}</span>
            {!! __('of') !!}
            <span class="font-medium">{{ $users->total() }}</span>
            {!! __('results') !!}
        </p>
    </div>
    <!-- Pagination -->
</div>
 