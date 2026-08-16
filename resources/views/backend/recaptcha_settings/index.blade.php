@extends('backend.partials.master')
@section('title')
{{ __('recaptcha_settings') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('recaptcha_settings') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="javascript:void(0)">{{ __('settings') }}</a> </li>
            <li>  {{ __('recaptcha_settings') }}</li>
        </ul>
    </div>
@endsection
@section('maincontent')

    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row">

                {{-- reCaptcha settings --}}
                <div class="col-lg-12">
                    <div class="dashboard--widget  mt-2"> 
                        <form action="{{ route('settings.recaptcha.update',['recaptcha'=>true]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
                                <div class="col-lg-4 ">
                                    <label for="site_key" class="form-label">{{ __('site_key') }}  </label>
                                    <input type="text" name="recaptcha_site_key" class="form-control form--control" id="site_key" value="{{ old('site_key',@settings('recaptcha_site_key')) }}" @if(hasPermission('recaptcha_settings_update') == false) disabled @endif >
                                </div>

                                <div class="col-lg-4  ">
                                    <label for="secret_key" class="form-label">{{ __('secret_key') }}  </label>
                                    <input type="text" name="recaptcha_secret_key" class="form-control form--control" id="secret_key" value="{{ old('secret_key',@settings('recaptcha_secret_key')) }}" @if(hasPermission('recaptcha_settings_update') == false) disabled @endif>
                                </div>

                                <div class="col-sm-4 mt-3 pt-25">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label cmr-10">{{ __('status') }}</label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="recaptcha_status" {{ old('status',@settings('recaptcha_status')) == 1? 'checked': '' }}  @if(hasPermission('recaptcha_settings_update') == false) disabled @endif >
                                        <label for="status" data-on-label="{{ __('enable') }}" data-off-label="{{ __('disable') }}"></label>
                                    </div>
                                </div>

                                @if (hasPermission('recaptcha_settings_update') !== false)
                                    <div class="col-md-12 mt-5   text-end">
                                        <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save_changes')}}</button>
                                    </div>
                                @endif

                            </div>

                        </form>


                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection


