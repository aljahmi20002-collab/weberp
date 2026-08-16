@extends('backend.partials.master')
@section('title')
    {{ __('holiday') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('holiday') }}</h5>
        <ul class="breadcrumb">
            <li><a href="#"> {{ __('hrm') }}</a> </li>
            <li><a href="#"> {{ __('attendance') }}</a> </li>
            <li> <a href="{{ route('hrm.attendance.holiday.index') }}">{{ __('holiday') }}</a> </li>
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

                            <a href="{{ route('hrm.attendance.holiday.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('hrm.attendance.holiday.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">

                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6  mt-3">
                                    <label for="from" class="form-label">{{ __('from') }} <span class="text-danger">*</span></label>
                                    <input type="text" readonly name="from_date" class="form-control form--control dateformat2" id="from" value="{{ old('from_date') }}">
                                    @error('from_date')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-lg-6  mt-3">
                                    <label for="to" class="form-label">{{ __('to') }} <span class="text-danger">*</span></label>
                                    <input type="text" readonly name="to_date" class="form-control form--control dateformat2" id="to" value="{{ old('to_date') }}">
                                    @error('to_date')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-4  mt-3">
                                    <label for="to" class="form-label">{{ __('file') }} </label>
                                    <input type="file" readonly name="to" class="form-control form--control " id="file" value="{{ old('file') }}">
                                    @error('file')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-2 mt-4 pt-lg-3">
                                    <div class="d-flex mt-3">
                                        <label class="form-label cmr-10">{{ __('status') }}</label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                    </div>
                                </div>

                                <div class="col-lg-12  mt-3">
                                    <label for="to" class="form-label">{{ __('note') }} </label>
                                     <textarea id="note" class="form-control form--control" name="note">{{ old('note') }}</textarea>
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
    <script src="{{ static_asset('backend') }}/assets/js/date-format.js"></script>
@endpush
