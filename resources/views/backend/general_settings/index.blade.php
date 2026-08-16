@extends('backend.partials.master')
@section('title')
    {{ __('general_settings') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('general_settings') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('user.index') }}">{{ __('settings') }}</a> </li>
            <li> {{ __('general_settings') }} </li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        @if (hasPermission('general_settings_update'))
                            <form action="{{ route('settings.general.settings.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                        @endif
                        <div class="row mt-3">

                            <div class="col-lg-6">
                                <label for="name" class="form-label">{{ __('name') }}</label>
                                <input type="text" name="name" class="form-control form--control" id="name"
                                    value="{{ old('name', settings('name')) }}">
                            </div>
                            <div class="col-lg-6">
                                <label for="email" class="form-label">{{ __('email') }} </label>
                                <input type="email" name="email" class="form-control form--control" id="email"
                                    value="{{ old('email', settings('email')) }}">
                            </div>


                            <div class="col-lg-3 mt-3">
                                <label for="phone" class="form-label">{{ __('phone') }}</label>
                                <input type="text" name="phone" class="form-control form--control" id="phone"
                                    value="{{ old('phone', settings('phone')) }}">
                                @error('phone')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-3 mt-3">
                                <label for="symbol" class="form-label">{{ __('currency') }} <span
                                        class="text-danger">*</span></label> 
                                <select class="form-control form--control select2" name="currency">
                                    <option selected disabled>{{ __('select') }} {{ __('currency') }}</option>
                                    @foreach (currency() as $currency)
                                        <option value="{{ $currency->symbol }}" @selected(old('currency',settings('currency')) == $currency->symbol) > {{ $currency->country }} (
                                            {{ $currency->code }} )</option>
                                    @endforeach
                                </select>
                                @error('currency')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                @enderror
                            </div>
 
                            <div class="col-lg-3 mt-3">
                                <label for="default_language" class="form-label">{{ __('default_language') }} <span  class="text-danger">*</span></label>
 
                                <select name="default_language" class="form-control form--control select2"
                                    id="default_language">
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->code }}" @selected($language->code == settings('default_language'))>
                                            {{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-lg-3 mt-3">
                                <label for="default_display_mode" class="form-label">{{ __('default_display_mode') }} <span
                                        class="text-danger">*</span></label>
                                <select name="default_display_mode" class="form-control form--control select2"
                                    id="default_display_mode">

                                    <option value="light"
                                        {{ 'light' == settings('default_display_mode') ? ' selected="selected"' : '' }}>
                                        {{ __('light') }}</option>
                                    <option value="night"
                                        {{ 'night' == settings('default_display_mode') ? ' selected="selected"' : '' }}>
                                        {{ __('night') }}</option>

                                </select>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label for="copyright" class="form-label">{{ __('copyright') }}</label>
                                <textarea name="copyright" id="copyright" class="form-control form--control" rows="10">{{ old('copyright', settings('copyright')) }}</textarea>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <div class="row mb-3">
                                    <div class="col-lg-6 ">
                                        <label for="bg-color" class="form-label">{{ __('theme_background_color') }}
                                        </label>
                                        <input type="color" name="theme_background_color" class="form-control "
                                            id="bg-color" value="{{ settings('theme_background_color') }}" />
                                    </div>
                                    <div class="col-lg-6 ">
                                        <label for="theme-text-color" class="form-label">{{ __('theme_text_color') }}
                                        </label>
                                        <input type="color" name="theme_text_color" class="form-control  "
                                            id="theme-text-color" value="{{ settings('theme_text_color') }}" />
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 text-center ">
                                        <div class="text-left">
                                            <label class="form-label">{{ __('logo') }}</label>
                                        </div>
                                        <div class="logo mb-5">
                                            <div class="thumb">
                                                <img class="logo-img" src="{{ settings('logo') }}" alt="clients">
                                            </div>
                                            <div class="remove-thumb">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="content">
                                                <div class="mt-2">
                                                    <label class="btn btn-sm btn-primary">
                                                        <i class="fas fa-camera"></i> {{ __('change') }}
                                                        {{ __('logo') }}
                                                        <input type="file" id="logo-image-upload" hidden=""
                                                            name="logo">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-center">
                                        <div class="text-left">
                                            <label class="form-label">{{ __('favicon') }}</label>
                                        </div>
                                        <div class="pupload mb-5">
                                            <div class="thumb">
                                                <img class="pu-img" src="{{ settings('favicon') }}" alt="clients">
                                            </div>
                                            <div class="remove-thumb">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="content">
                                                <div class="mt-2">
                                                    <label class="btn btn-sm btn-primary">
                                                        <i class="fas fa-camera"></i> {{ __('change') }}
                                                        {{ __('favicon') }}
                                                        <input type="file" id="profile-image-upload" hidden=""
                                                            name="favicon">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-6 mt-3 text-center">
                                        <div class="text-left">
                                            <label class="form-label">{{ __('table_empty_image') }}</label>
                                        </div>
                                        <div class="table_empty_image mb-5">
                                            <div class="thumb">
                                                <img class="table_empty_image-img object-fit-contain"
                                                    src="{{ settings('table_empty_image') }}" alt="clients"
                                                    width="50%" height="200px">
                                            </div>
                                            <div class="remove-thumb">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="content">
                                                <div class="mt-2">
                                                    <label class="btn btn-sm btn-primary">
                                                        <i class="fas fa-camera"></i> {{ __('change') }}
                                                        {{ __('table_empty_image') }}
                                                        <input type="file" id="table_empty_image-image-upload"
                                                            hidden="" name="table_empty_image">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-3 text-center">
                                        <div class="text-left">
                                            <label class="form-label">{{ __('table_search_image') }}</label>
                                        </div>
                                        <div class="table_search_image mb-5">
                                            <div class="thumb">
                                                <img class="table_search_image-img object-fit-contain"
                                                    src="{{ settings('table_search_image') }}" alt="clients"
                                                    width="50%" height="200px">
                                            </div>
                                            <div class="remove-thumb">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="content">
                                                <div class="mt-2">
                                                    <label class="btn btn-sm btn-primary">
                                                        <i class="fas fa-camera"></i> {{ __('change') }}
                                                        {{ __('table_search_image') }}
                                                        <input type="file" id="table_search_image-image-upload"
                                                            hidden="" name="table_search_image">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        @if (hasPermission('general_settings_update'))
                            <div class="col-md-12 mt-5 text-end">
                                <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i
                                        class="fa fa-save"></i> {{ __('save_changes') }}</button>
                            </div>
                        @endif
                    </div>
                    @if (hasPermission('general_settings_update'))
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ static_asset('backend/assets') }}/js/generalsettings/gs.js"></script>
@endpush
