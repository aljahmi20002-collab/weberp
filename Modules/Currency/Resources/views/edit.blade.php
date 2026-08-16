@extends('backend.partials.master')
@section('title')
    {{ __('currency') }} {{ __('edit') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('currency') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('settings') }} </a></li>
            <li> <a href="{{ route('settings.currency.index') }}">{{ __('currency') }}</a> </li>
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

                            <a href="{{ route('settings.currency.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('settings.currency.update',['id'=>$currency->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">

                                <div class="col-lg-6 mt-3">
                                    <label for="country" class="form-label">{{ __('country') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="country" class="form-control form--control" id="country" value="{{ old('country',$currency->country) }}">
                                    @error('country')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="currency" class="form-label">{{ __('currency') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="currency" class="form-control form--control" id="currency" value="{{ old('currency',$currency->currency) }}">
                                    @error('currency')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="code" class="form-label">{{ __('code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control form--control" id="code" value="{{ old('code',$currency->code) }}">
                                    @error('code')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="symbol" class="form-label">{{ __('symbol') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="symbol" class="form-control form--control" id="symbol" value="{{ old('symbol',$currency->symbol) }}">
                                    @error('symbol')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="position" class="form-label">{{ __('position') }} </label>
                                    <input type="text" name="position" class="form-control form--control" id="position" value="{{ old('position',$currency->position) }}">
                                    @error('position')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                @enderror
                                </div>
                                <div class="col-lg-6 mt-4 pt-lg-3">
                                    <div class="d-flex mt-3">
                                        <label class="form-label cmr-10">{{ __('status') }}</label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',$currency->status) ==null? '':'checked' }} >
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
