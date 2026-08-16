@extends('backend.partials.master')
@section('title')
    {{ __('duty_schedule') }} {{ __('edit') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('duty_schedule') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('hrm') }}</a>  </li>
            <li> <a href="#">{{ __('attendance') }}</a> </li>
            <li> <a href="{{ route('hrm.attendance.duty.schedule.index') }}">{{ __('duty_schedule') }}</a> </li>
            <li>  {{ __('edit') }} </li>
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

                            <a href="{{ route('hrm.attendance.duty.schedule.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('hrm.attendance.duty.schedule.update',['id'=>$duty_schedule->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
                                <div class="col-lg-6 mt-3">
                                    <label for="role_id" class="form-label">{{ __('role') }} <span class="text-danger">*</span></label>
                                    <select class=" form-control form--control select2" name="role_id" id="role_id">
                                        <option  selected disabled> {{ __('select') }} {{ __('role') }}</option>
                                        @foreach ($roles as $role)
                                            <option  value="{{ @$role->id }}"  @if(old('role_id',@$duty_schedule->role_id) == $role->id) selected @endif> {{ @$role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="start_time" class="form-label">{{ __('start_time') }}  <span class="text-danger">*</span></label>
                                    <input type="time"  name="start_time" class="form-control form--control time" id="start_time" value="{{ old('start_time',@$duty_schedule->start_time) }}">
                                    @error('start_time')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="end_time" class="form-label">{{ __('end_time') }}  <span class="text-danger">*</span></label>
                                    <input type="time"  name="end_time" class="form-control form--control" id="end_time" value="{{ old('end_time',@$duty_schedule->end_time) }}">
                                    @error('end_time')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
