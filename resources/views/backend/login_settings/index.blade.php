@extends('backend.partials.master')
@section('title')
    {{ __('login_settings') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('login_settings') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="javascript:void(0)">{{ __('settings') }}</a> </li>
            <li> {{ __('login_settings') }}</li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row">

                {{-- facebook --}}
                <div class="col-lg-6">
                    <div class="dashboard--widget  mt-2">
                        <h4 class="card-title overflow-hidden">{{ __('facebook') }} {{ __('login') }}</h4>
                        @if (hasPermission('login_settings_update'))
                            <form action="{{ route('settings.login.settings.update', ['facebook' => true]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                        @endif
                        <div class="row mt-3">
                            <div class="col-lg-12 ">
                                <label for="facebook_client_id" class="form-label">{{ __('client_id') }} </label>
                                <input type="text" name="facebook_client_id" class="form-control form--control"
                                    id="facebook_client_id"
                                    value="{{ old('facebook_client_id', settings('facebook_client_id')) }}"
                                    @if (hasPermission('login_settings_update') == false) disabled @endif>
                            </div>

                            <div class="col-lg-12 mt-2">
                                <label for="facebook_client_secret" class="form-label">{{ __('client_secret') }} </label>
                                <input type="text" name="facebook_client_secret" class="form-control form--control"
                                    id="facebook_client_secret"
                                    value="{{ old('facebook_client_secret', settings('facebook_client_secret')) }}"
                                    @if (hasPermission('login_settings_update') == false) disabled @endif>
                            </div>

                            <div class="col-sm-12  pt-25">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label cmr-10">{{ __('status') }}</label>
                                    <input type="checkbox" class="status" id="facebook_status" switch="success"
                                        name="facebook_status"
                                        {{ old('facebook_status', settings('facebook_status')) == 1 ? 'checked' : '' }}>
                                    <label for="facebook_status" data-on-label="{{ __('enable') }}"
                                        data-off-label="{{ __('disable') }}"></label>
                                </div>
                            </div>

                            @if (hasPermission('login_settings_update'))
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i
                                            class="fa fa-save"></i> {{ __('save_changes') }}</button>
                                </div>
                            @endif
                        </div>
                        @if (hasPermission('login_settings_update'))
                            </form>
                        @endif

                    </div>
                </div>


                {{-- google --}}
                <div class="col-lg-6">
                    <div class="dashboard--widget  mt-2">
                        <h4 class="card-title overflow-hidden">{{ __('google') }} {{ __('login') }}</h4>
                        @if (hasPermission('login_settings_update'))
                            <form action="{{ route('settings.login.settings.update', ['google' => true]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                        @endif

                        <div class="row mt-3">

                            <div class="col-lg-12 ">
                                <label for="google_client_id" class="form-label">{{ __('client_id') }} </label>
                                <input type="text" name="google_client_id" class="form-control form--control"
                                    id="google_client_id"
                                    value="{{ old('google_client_id', settings('google_client_id')) }}"
                                    @if (hasPermission('login_settings_update') == false) disabled @endif>
                            </div>

                            <div class="col-lg-12 mt-2">
                                <label for="google_client_secret" class="form-label">{{ __('client_secret') }} </label>
                                <input type="text" name="google_client_secret" class="form-control form--control"
                                    id="google_client_secret"
                                    value="{{ old('google_client_secret', settings('google_client_secret')) }}"
                                    @if (hasPermission('login_settings_update') == false) disabled @endif>
                            </div>


                            <div class="col-sm-12  pt-25">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label cmr-10">{{ __('status') }}</label>
                                    <input type="checkbox" class="status" id="google_status" switch="success"
                                        name="google_status"
                                        {{ old('google_status', settings('google_status')) == 1 ? 'checked' : '' }}>
                                    <label for="google_status" data-on-label="{{ __('enable') }}"
                                        data-off-label="{{ __('disable') }}"></label>
                                </div>
                            </div>

                            @if (hasPermission('login_settings_update'))
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i
                                            class="fa fa-save"></i> {{ __('save_changes') }}</button>
                                </div>
                            @endif
                        </div>
                        @if (hasPermission('login_settings_update'))
                            </form>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
