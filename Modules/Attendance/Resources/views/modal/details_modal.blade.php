 
    <div class="row">
        @if(hasPermission('attendance_delete') || hasPermission('attendance_update'))
            <div class="col-12">
                <div class="dropdown text-right">
                    <a href="#" class="dropdown-toggle text-primary" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ...
                    </a>
                    <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if(hasPermission('attendance_delete'))
                        <form action="{{ route('hrm.attendance.delete',@$attendance->id) }}"  method="post" >
                            @csrf
                            @method('delete')
                            <button type="submit" class="dropdown-item" >
                                {{ __('delete') }}
                            </button>
                        </form>
                        @endif
                        @if(hasPermission('attendance_update'))
                        <a href="#"  class="dropdown-item modalBtn"   data-bs-target="#dynamic-modal"  data-title="{{ __('edit_attendance') }}" data-url="{{ route('hrm.attendance.edit.modal',['id'=>$attendance->id]) }}" >
                            {{ __('edit') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="row d-flex text-left">
                <div class="col-2">
                    <img src="{{ @$attendance->user->image }}" width="60"/>
                </div>
                <div class="col-10">
                    <strong>{{@$attendance->user->name}}</strong>
                    <p>{{@$attendance->user->usertypes}}</p>
                </div>
                
            </div>
        </div>
     
        <div class="col-6  mt-3">
            <div>
                <label  class="form-label">{{ __('date') }} </label>:
                <label> {{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</label>    
            </div>
            <div>
                <label for="check_in" class="form-label">{{ __('check_in') }} </label><br/>
                <label>{{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}</label>
            </div>
            <div class="mt-3">
                <label for="check_out" class="form-label">{{ __('check_out') }} </label><br/>
                <label>
                     @if($attendance->status == \Modules\Attendance\Enums\AttendanceStatus::PENDING)
                         {{ __('did_not_check_out') }}
                     @else
                         {{ \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') }}
                     @endif
                 </label>
            </div>
        </div> 
        <div class="col-6  mt-3 m-auto">
            <div class="rounded-circle stay-time">
                <label>
                    @if($attendance->status == \Modules\Attendance\Enums\AttendanceStatus::PENDING)
                        {{ __('did_not_check_out') }}
                    @else
                        {{  @$attendance->staytime}}
                    @endif
                </label>
            </div>
        </div> 
    </div>

