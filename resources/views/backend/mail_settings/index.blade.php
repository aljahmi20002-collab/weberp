@extends('backend.partials.master')
@section('title')
    {{ __('mail_settings') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('mail_settings') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="javascript:void(0)">{{ __('settings') }}</a> </li>
            <li> {{ __('mail_settings') }}</li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <form action="{{ route('settings.mail.settings.update') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="mail_driver" class="form-label">{{ __('mail_driver') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="mail_driver" class="form-control form--control select2" id="mail_driver">
                                        <option value="sendmail"
                                            {{ old('mail_driver', settings('mail_driver')) == 'sendmail' ? 'selected' : '' }}>
                                            {{ __('sendmail') }}</option>
                                        <option value="smtp"
                                            {{ old('mail_driver', settings('mail_driver')) == 'smtp' ? 'selected' : '' }}>
                                            {{ __('smtp') }}</option>
                                    </select>
                                    @error('mail_driver')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 sendmail">
                                    <label for="sendmail_path" class="form-label">{{ __('path') }} </label>
                                    <input type="text" name="sendmail_path" class="form-control form--control"
                                        id="sendmail_path" value="{{ old('sendmail_path', settings('sendmail_path')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_host" class="form-label">{{ __('mail_host') }} </label>
                                    <input type="text" name="mail_host" class="form-control form--control" id="mail_host"
                                        value="{{ old('mail_host', settings('mail_host')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_port" class="form-label">{{ __('mail_port') }} </label>
                                    <input type="text" name="mail_port" class="form-control form--control" id="mail_port"
                                        value="{{ old('mail_port', settings('mail_port')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_address" class="form-label">{{ __('mail_address') }} </label>
                                    <input type="email" name="mail_address" class="form-control form--control"
                                        id="mail_address" value="{{ old('mail_address', settings('mail_address')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_name" class="form-label">{{ __('name') }} </label>
                                    <input type="text" name="mail_name" class="form-control form--control" id="mail_name"
                                        value="{{ old('mail_name', settings('mail_name')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_username" class="form-label">{{ __('mail_username') }} </label>
                                    <input type="text" name="mail_username" class="form-control form--control"
                                        id="mail_username" value="{{ old('mail_username', settings('mail_username')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_password" class="form-label">{{ __('mail_password') }} </label>
                                    <input type="password" name="mail_password" class="form-control form--control"
                                        id="mail_password" value="{{ old('mail_password', settings('mail_password')) }}">
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="mail_encryption" class="form-label">{{ __('mail_encryption') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="mail_encryption" class="form-control form--control " id="mail_encryption">
                                        <option value="">Null</option>
                                        <option @if (settings('mail_encryption') == 'tls') selected @endif value="tls">Tls
                                        </option>
                                        <option @if (settings('mail_encryption') == 'ssl') selected @endif value="ssl">Ssl
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-6 smtp">
                                    <label for="signature" class="form-label">{{ __('signature') }} </label>
                                    <textarea class="form-control form--control" id="signature" name="signature">{{ old('signature', settings('signature')) }}</textarea>
                                </div>

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i
                                            class="fa fa-save"></i> {{ __('save_changes') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden">{{ __('mailsendtest') }}</h4>

                        <form action="{{ route('settings.mail.settings.testsendmail') }}" method="post">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-lg-9 ">
                                    <label for="email" class="form-label">{{ __('email') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="email" class="form-control form--control"
                                        id="email" value="{{ old('email') }}">
                                    @error('email')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror

                                </div>

                                <div class="col-lg-3 text-start">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm cmt30 mailtestbtn"> <i
                                            class="fa fa-save"></i> {{ __('test') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush


@push('scripts')
    <script src="{{ static_asset('backend/assets') }}/js/mailsettings/ms.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if (settings('mail_driver') == 'smtp')
                $('.smtp').show();
                $('.sendmail').hide();
            @elseif (settings('mail_driver') == 'sendmail')
                $('.sendmail').show();
                $('.smtp').hide();
            @endif
        })
    </script>
@endpush
