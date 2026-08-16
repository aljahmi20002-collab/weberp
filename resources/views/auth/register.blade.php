@extends('layouts.app')
@section('title',__('business_register'))
@push('styles')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@section('maincontent')
      <!-- Auth Register Section -->
<section class="auth-section">
    <div class="auth-content  width700">
        <div class="text-center mb-5">
            <img src="{{ settings('logo') }}"/>
        </div>

        <h3 class="title">{{ __('business_register') }}</h3>
        <form action="{{ route('register') }}" method="post" style="margin-top:0px" enctype="multipart/form-data">
                @csrf
            <div class="row gy-4">
                <div class="col-sm-12 mt-2">
                    @if(session('status'))
                        <div class="alert alert-success d-inline-block align-items-center" role="alert">{!! session('status') !!}</div>
                    @endif
                </div>

                    <h6 class="title">{{ __('business_details') }}</h6>
                    <div class="col-sm-6 mt-2">
                        <label for="business_name" class="form-label">{{ __('business_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" class="form-control form--control" id="business_name" value="{{ old('business_name') }}">
                        @error('business_name')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="start_date" class="form-label">{{ __('start_date') }} <span class="text-danger">*</span></label>
                        <input type="text" name="start_date" class="form-control form--control dateofbirth" id="start_date" value="{{ old('start_date',date('d/m/Y')) }}">
                        @error('start_date')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="logo" class="form-label">{{ __('logo') }} <span class="text-danger">*</span></label>
                        <input type="file" name="logo" class="form-control form--control " id="logo">
                    </div>

                    <div class="col-sm-6 mt-2">
                        <label for="currency" class="form-label">{{ __('currency') }} <span class="text-danger">*</span></label>
                         <select class="form-control form--control select2" name="currency">
                            <option selected disabled>{{ __('select') }} {{ __('currency') }}</option>
                            @foreach (currency() as $currency)
                                <option value="{{ $currency->id }}" @if(old('currency') == $currency->id) selected @endif>{{ $currency->country }} ( {{ $currency->code }} )</option>
                            @endforeach
                         </select>
                        @error('currency')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="col-sm-6 mt-2">
                        <label for="website" class="form-label">{{ __('website') }} <span class="text-danger">*</span></label>
                        <input type="text" name="website" class="form-control form--control" id="website" value="{{ old('website') }}">
                        @error('website')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-sm-6 mt-2">
                        <label for="business_phone" class="form-label">{{ __('business_phone') }} <span class="text-danger">*</span></label>
                        <input type="text" name="business_phone" class="form-control form--control" id="business_phone" value="{{ old('business_phone') }}">
                        @error('business_phone')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-sm-6 mt-2">
                        <label for="country" class="form-label">{{ __('country') }} <span class="text-danger">*</span></label>
                         <select class="form-control form--control select2" name="country">
                            <option selected disabled>{{ __('select') }} {{ __('country') }}</option>
                            @foreach (currency() as $currency)
                                <option value="{{ $currency->id }}" @if(old('country') == $currency->id) selected @endif>{{ $currency->country }}</option>
                            @endforeach
                         </select>
                        @error('country')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-sm-6 mt-2">
                        <label for="state" class="form-label">{{ __('state') }} <span class="text-danger">*</span></label>
                        <input type="text" name="state" class="form-control form--control" id="state" value="{{ old('state') }}">
                        @error('state')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-2">
                        <label for="city" class="form-label">{{ __('city') }} <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control form--control" id="city" value="{{ old('city') }}">
                        @error('city')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-2">
                        <label for="zip_code" class="form-label">{{ __('zip_code') }} <span class="text-danger">*</span></label>
                        <input type="text" name="zip_code" class="form-control form--control" id="zip_code" value="{{ old('zip_code') }}">
                        @error('zip_code')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <h6 class="title">{{ __('owner_information') }}</h6>
                    <div class="col-sm-6 mt-0">
                        <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-sm-6 mt-0">
                        <label for="email" class="form-label">{{ __('email') }} <span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control form--control" id="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-sm-6 mt-2">
                        <label for="password" class="form-label">{{ __('password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form--control " id="password" value="{{ old('password') }}">
                        @error('password')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label for="password_confirmation" class="form-label">{{ __('password_confirmation') }} <span class="text-danger">*</span></label>
                        <input type="password"  name="password_confirmation"   autocomplete="new-password"  class="form-control form--control" id="password_confirmation" value="{{ old('password') }}">
                        @error('password_confirmation')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    @if(settings('recaptcha_status') && settings('recaptcha_status') == 1)
                        <div class="col-sm-6 mt-2">
                                <div class="g-recaptcha" data-sitekey="{{ settings('recaptcha_site_key') }}"></div>
                                @error('g-recaptcha-response')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                @enderror
                        </div>
                    @endif
                    <div class="col-sm-6 mt-2">
                        <div class="d-flex flex-wrap justify-content-between mt-2">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1" @if(old('remember') && old('remember') !=='') checked @endif  >
                                <label for="remember" class="form-check-label">{{ __('i_agree_with_the_terms_of_service_and_policy_privacy') }}
                                </label>
                            </div>
                        </div>
                    </div>

                <div class="col-sm-12 mt-5 text-center ">
                    <button class="btn w-md-25 btn-primary btn-lg" id="signUp" type="submit">{{ __('auth.sign_up') }}</button>
                </div>


                <div class="col-sm-12 text-center">
                    <div class="mb-3 mt-4">
                        {{ __('auth.already_have_an_account') }} ? <a href="{{ route('login') }}" class="text-base">Sign In</a>
                    </div>
                    <div class="or">
                        <span>Or</span>
                    </div>
                    <ul class="other-auth-options">
                        <li>
                            <a href="{{ route('social.login',['facebook']) }}" class="facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('social.login',['google']) }}" class="google">
                                <i class="fab fa-google"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
        </div>
    </section>
    <!-- Auth Register Section -->
  @endsection

@push('scripts')
    <script src="{{static_asset('backend/assets')}}/js/auth/register.js"></script>
    <script type="text/javascript">
         @if (old('remember') && old('remember') !== "")
                $("#signUp").prop('disabled', false);
         @endif
    </script>
@endpush
