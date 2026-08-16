@extends('backend.partials.master')
@section('title')
    {{ __('leave_assign') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('leave_assign') }}</h5>
        <ul class="breadcrumb">
            <li><a href="#"> {{ __('hrm') }}</a> </li>
            <li> <a href="{{ route('hrm.leave.assign.index') }}">{{ __('leave_assign') }}</a> </li>
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
                            <a href="{{ route('hrm.leave.assign.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('hrm.leave.assign.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">

                                <div class="col-lg-6 mt-3">
                                    <label for="type_id" class="form-label">{{ __('leave_type') }} <span class="text-danger">*</span></label>
                                    <select name="type_id" class="form-control form--control select2" id="type_id">
                                        <option disabled selected>{{ __('select') }} {{ __('leave_type') }}</option>
                                        @foreach ($leave_types as $leave_type)
                                            <option value="{{ $leave_type->id}}" @if(old('type_id') == $leave_type->id) selected  @endif> {{ @$leave_type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6 mt-3">
                                    <label for="role_id" class="form-label">{{ __('role') }} <span class="text-danger">*</span></label>
                                    <select name="role_id" class="form-control form--control select2" id="role_id">
                                        <option disabled selected>{{ __('select') }} {{ __('role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" @if(old('role_id') == $role->id) selected  @endif>{{ @$role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6 mt-3">
                                    <label for="days" class="form-label">{{ __('days') }} <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('days') }}" class="form-control form--control" name="days"/>
                                    @error('days')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-4 pt-lg-3">
                                    <div class="d-flex mt-3">
                                        <label class="form-label cmr-10">{{ __('status') }}</label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                    </div>
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
