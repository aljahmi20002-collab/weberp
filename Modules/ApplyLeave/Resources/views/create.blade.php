@extends('backend.partials.master')
@section('title')
    {{ __('apply_leave') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('apply_leave') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('hrm') }} </a></li>
            <li> <a href="{{ route('hrm.apply.leave.index') }}">{{ __('apply_leave') }}</a> </li>
            <li>  {{ __('create') }} </li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden"> 

                            <a href="{{ route('hrm.apply.leave.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('hrm.apply.leave.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">

                                @if(isSuperadmin() || business())
                                <div class="col-lg-6 mt-3">
                                    <label for="employee_id" class="form-label">{{ __('employee') }} <span class="text-danger">*</span></label>
                                   <select class="form-control form--control select2" name="employee_id" id="employee_id" data-url="{{ route('hrm.apply.leave.assigned.leave') }}">
                                       <option selected disabled>{{ __('select') }} {{ __('employee') }}</option>
                                       @foreach ($users as $user)
                                            <option value="{{ @$user->id }}" @if (old('employee_id') == $user->id) selected @endif>{{ @$user->name }}</option>
                                       @endforeach
                                   </select>
                                    @error('employee_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                                
                                <div class="col-lg-6 mt-3">
                                    <label for="leave_assign_id" class="form-label">{{ __('leave_type') }} <span class="text-danger">*</span></label>
                                   <select class="form-control form--control select2" name="leave_assign_id" id="leave_assign_id">
                                       <option selected disabled>{{ __('select') }} {{ __('leave_type') }}</option>
                                       @if(isSuperadmin() || business())
                                       @else
                                            @foreach ($leave_assigns as $leave_assign)
                                                    <option value="{{ @$leave_assign->id }}" @if (old('leave_assign_id') == $leave_assign->id) selected @endif>{{ @$leave_assign->leaveType->name }}</option>
                                            @endforeach
                                       @endif
                                   </select>
                                    @error('leave_assign_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6 mt-3">
                                    <label for="manager" class="form-label">{{ __('manager') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="manager" class="form-control form--control" id="manager" value="{{ old('manager',old('manager')) }}">
                                    @error('manager')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6 mt-3">
                                    <label for="leave_from" class="form-label">{{ __('leave_from') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="leave_from" class="form-control form--control dateformat2" id="leave_from" value="{{ old('leave_from',old('leave_from')) }}" readonly>
                                    @error('leave_from')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="leave_to" class="form-label">{{ __('leave_to') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="leave_to" class="form-control form--control dateformat2" id="leave_to" value="{{ old('leave_to',old('leave_to')) }}" readonly>
                                    @error('leave_to')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label for="file" class="form-label">{{ __('file') }} </label>
                                    <input type="file" name="file" class="form-control form--control " id="file">
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label for="reason" class="form-label">{{ __('reason') }}  </label>
                                    <textarea name="reason" class="form-control form--control">{{ old('reason') }}</textarea>
                                </div>

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{static_asset('backend/assets')}}/js/date-format.js"></script>
    <script src="{{static_asset('backend')}}/js/applyleave/applyleave.js"></script>
@endpush
