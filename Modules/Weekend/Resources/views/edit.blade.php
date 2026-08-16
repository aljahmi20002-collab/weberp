@extends('backend.partials.master')
@section('title')
    {{ __('weekend') }} {{ __('edit') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('weekend') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('hrm') }}</a> </li>
            <li> <a href="#">{{ __('attendance') }}</a> </li>
            <li> <a href="{{ route('hrm.attendance.weekend.index') }}">{{ __('weekend') }}</a> </li>
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

                            <a href="{{ route('hrm.attendance.weekend.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('hrm.attendance.weekend.update',['id'=>$weekend->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name',@$weekend->name) }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="weekend" class="form-label">{{ __('weekend') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="weekend" >
                                        <option value="{{ \Modules\Weekend\Enums\WeekendStatus::WEEKEND_YES }}" @if(old('weekend',@$weekend->is_weekend) == \Modules\Weekend\Enums\WeekendStatus::WEEKEND_YES) selected @endif>{{ __('yes') }}</option>
                                        <option value="{{ \Modules\Weekend\Enums\WeekendStatus::WEEKEND_NO }}" @if(old('weekend',@$weekend->is_weekend) == \Modules\Weekend\Enums\WeekendStatus::WEEKEND_NO) selected @endif>{{ __('no') }}</option>
                                    </select>
                                    @error('weekend')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6  mt-3">
                                    <label for="position" class="form-label">{{ __('position') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="position" class="form-control form--control" id="position" value="{{ old('position',@$weekend->position) }}">
                                    @error('position')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-4 pt-lg-3">
                                    <div class="d-flex mt-3">
                                        <label class="form-label cmr-10">{{ __('status') }} <span class="text-danger">*</span></label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',@$weekend->status) == App\Enums\Status::INACTIVE? '':'checked' }} >
                                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                    </div>
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
