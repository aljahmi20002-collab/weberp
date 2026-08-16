@extends('backend.partials.master')
@section('title')
    {{ __('language') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('languages') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('language.index') }}">{{ __('languages') }}</a> </li>
            <li> {{ __('create') }} </li>
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

                            <a href="{{ route('language.index') }}" class="btn btn-sm btn-primary float-right"
                                data-bs-toggle="tooltip" title="{{ __('back') }}" data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('language.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="name" class="form-label">{{ __('lang_name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control" id="name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="code" class="form-label">{{ __('short_code') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control form--control" id="code"
                                        value="{{ old('code') }}">
                                    @error('code')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="flag" class="form-label ">{{ __('flag') }} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form--control selectpicker select2" id="flag"
                                        name="icon_class">
                                        <option selected disabled>{{ __('select') }} {{ __('flag') }}</option>
                                        @foreach ($flags as $flag)
                                            <option value="{{ $flag->icon_class }}" data-icon="{{ $flag->icon_class }}"
                                                class="">{{ $flag->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('icon_class')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="script" class="form-label">{{ __('script') }} </label>
                                    <input type="text" name="script" class="form-control form--control" id="script"
                                        value="{{ old('script') }}">
                                    @error('script')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6  mt-3">
                                    <label for="native" class="form-label">{{ __('native') }}</label>
                                    <input type="text" name="native" class="form-control form--control" id="native"
                                        value="{{ old('native') }}">
                                    @error('native')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="regional" class="form-label">{{ __('regional') }} </label>
                                    <input type="text" name="regional" class="form-control form--control" id="regional"
                                        value="{{ old('regional') }}">
                                    @error('regional')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-6 mt-4">
                                    <div class="row">

                                        <div class="col-sm-7">
                                            <div class="d-flex">
                                                <label class="form-label cmr-10">{{ __('text_direction') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="mr-2 form-check-input" id="LTR"
                                                        name="text_direction" value="LTR"
                                                        {{ old('text_direction') == 'LTR' ? 'checked' : 'checked' }}>
                                                    <label class="form-check-label"
                                                        for="LTR">{{ __('ltr') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="mr-2 form-check-input" id="RTL"
                                                        name="text_direction" value="RTL"
                                                        {{ old('text_direction') == 'RTL' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="RTL">{{ __('rtl') }}</label>
                                                </div>
                                                <div class="form-group">
                                                    @error('text_direction')
                                                        <p class="text-danger pt-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="">
                                                <div class="d-flex">
                                                    <label class="form-label cmr-10">{{ __('status') }}</label>
                                                    <input type="checkbox" class="status" id="status"
                                                        switch="success" name="status"
                                                        {{ old('status', \App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE ? '' : 'checked' }}>
                                                    <label for="status"
                                                        data-on-label="{{ __('status.' . App\Enums\Status::ACTIVE) }}"
                                                        data-off-label="{{ __('status.' . App\Enums\Status::INACTIVE) }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i
                                            class="fa fa-save"></i> {{ __('save') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
