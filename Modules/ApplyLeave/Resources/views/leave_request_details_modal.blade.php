
<div id="leave_modal" class="leave_modal">
    <div class="row">
        <div class="col-md-12"  >
            <h5 class=" overflow-hidden text-end " >
                <a  href="{{ route('hrm.leave.request.print',$leave_request->id) }}" target="_blank" class="btn print-btn btn-sm btn-primary float-right"><i class="fa fa-print"></i></a>  
            </h5>
            <div  class='text-center'>
                <div class="author border-bottom"> 
                    <img src="{{ @$leave_request->user->business_info->logo_img}}" class="m-0" alt="images" width="50"><br/>
                    {{ @$leave_request->user->business_info->business_name}}
                </div> 
            </div>
        </div>
        <div class="col-md-12 "  >
            <div  class=' text-left'>
                    <h5 class="d-inline-block mt-2 " >{{ __('leave_information') }}</h5>
            </div>
        </div>
        <div class="col-md-12 mx-auto">
            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('employee_name') }} :</label>
                <div class="col-md-9">
                  {{ __('employee_name') }}
                </div>
            </div>
            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('user_type') }}:</label>
                <div class="col-md-9">
                   {{@user_type_text($leave_request->user->user_type)}}
                </div>
            </div>
            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('business_name') }}:</label>
                <div class="col-md-9">
                   {{@business_name($leave_request->user->id)}}
                </div>
            </div>
            @if($leave_request->user->user_type == \App\Enums\UserType::USER)
                <div class="form-group items-center row mt-4">
                    <label class="col-md-3 col-from-label m-0">{{ __('branch_name') }} :</label>
                    <div class="col-md-9">
                       {{@user_branch_name($leave_request->user->id)}}
                    </div>
                </div>
            @endif

            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('manager') }}:</label>
                <div class="col-md-9">
                 {{@$leave_request->manager}}
                </div>
            </div>
            <div class="form-group items-center row mt-4 leave-type">
                <label class="col-md-3 col-from-label m-0" style="white-space: pre-line">{{ __('type_of_absence_requested') }} :</label>
                <div class="col-md-9">
                    <i class="fa fa-tick"></i>
                    @foreach ($assigned_leave_types as $type)
                        <div class="radio mar-btm float-left mr-3">
                             @if (@$leave_request->type_id == @$type->leavetype->id) <i class="fa fa-check"></i> @else<input  type="checkbox" class="weekend_check_input" type="checkbox" disabled/> @endif 
                            <label for="glass{{@$type->leavetype->id}}">{{@$type->leavetype->name}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('applicant_attacehed_file') }} : </label>
                <div class="col-md-9 pl-3" >
                        <a target="_blank" href="{{ @$leave_request->file_path }}" download>{{ __('download') }}</a>
                </div>
            </div>

            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('date_Of_absence') }}: </label>
                <div class="col-md-9 row items-center">
                    <div class="col-md-2">
                        <label for="">{{ __('from') }}:</label>
                    </div>
                    <div class="col-md-4">
                       {{@dateFormat2(@$leave_request->leave_from)}}
                    </div>
                    <div class="col-md-2">
                        <label for="">To:</label>
                    </div>
                    <div class="col-md-4">
                       {{@dateFormat2(@$leave_request->leave_to)}}
                    </div>


                </div>
            </div>

            <div class="form-group  row mt-4 mb-5">
                <label class="col-md-3 col-from-label m-0">{{ __('reason_for_absence') }}:</label>
                <div class="col-md-9">
                   {{@$leave_request->reason}}
                </div>
            </div>

            <div class="form-group items-center row mt-4">
                <div class="col-md-3"></div>
                <div class="col-md-9 row text-center">
                    <div class="col-md-6">
                        <div>
                            ------------------------
                        </div>
                        <label>{{ __('employee_signature') }}</label>
                    </div>

                    <div class="col-md-6">
                        <div>
                            ------------------------
                        </div>
                        <label >{{ __('date') }}</label>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12 mx-auto  my-3"  >
            <h5 class="text-left py-2  ">{{ __('manager_approval') }}</h5>
        </div>
        <div class="col-md-12 mx-auto">
            <div class="form-group items-center row mt-4">
                <label class="col-md-3 col-from-label m-0">{{ __('approval_status') }} :</label>
                <div class="col-md-9 leave-type">
                        <div class="radio mar-btm float-left mr-3">
                            @if (@$leave_request->status==\Modules\ApplyLeave\Enums\LeaveStatus::PENDING) <i class="fa fa-check"></i> @else <input id="pending"  class="weekend_check_input" type="checkbox" disabled>  @endif
                            <label for="pending">{{ __('pending') }}</label>
                        </div>
                        <div class=" radio mar-btm float-left mr-3">
                             @if (@$leave_request->status==\Modules\ApplyLeave\Enums\LeaveStatus::APPROVED) <i class="fa fa-check"></i> @else <input id="approved" class="weekend_check_input" type="checkbox" disabled> @endif
                            <label for="approved">{{ __('approved') }}</label>
                        </div>
                        <div class=" radio mar-btm float-left mr-3">
                             @if (@$leave_request->status==\Modules\ApplyLeave\Enums\LeaveStatus::REJECTED) <i class="fa fa-check"></i> @else <input id="rejected"  class="weekend_check_input" type="checkbox" disabled> @endif 
                            <label for="rejected">{{ __('rejected') }}</label>
                        </div>
                </div>
            </div>

        <div class="form-group  row mt-4 mb-5">
            <label class="col-md-3 col-from-label m-0">{{ __('comments') }} :</label>
            <div class="col-md-9">
              
            </div>
        </div>
        <div class="form-group row mt-4">
            <div class="col-md-3"></div>
            <div class="col-md-9 row text-center">
                <div class="col-md-6">
                    <div>
                        ------------------------
                    </div>
                    <label>{{ __('manager_signature') }}</label>
                </div>

                <div class="col-md-6">
                    <div>
                        ------------------------
                    </div>
                    <label >{{ __('date') }}</label>
                </div>

            </div>
        </div>
    </div>

</div>
