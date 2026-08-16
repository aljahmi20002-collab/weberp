@extends('layouts.app')
@section('title',__('reset_password'))
@section('maincontent')




   <!-- Auth Login Section -->
   <section class="auth-section">
    <div class="auth-content ">
        <div class="text-center mb-5">
            <img src="{{ settings('logo') }}"/>
        </div>
        <h3 class="title">{{__('reset_password') }}</h3>
        <div class="row gy-4">

             <form action="{{ route('custom.password.update') }}" method="post">
                 @csrf
                 <input type="hidden" name="token" value="{{ $token }}">

                 <div class="col-sm-12 ">
                     {{-- <label for="email" class="form-label">{{ __('email_address') }} <span class="text-danger">*</span></label> --}}
                     <input type="hidden" name="email" class="form-control form--control" id="email" value="{{ $email ?? old('email') }}" required >
                     @error('email')
                         <p class="text-danger pt-2">{{ $message }}</p>
                     @enderror
                 </div>
                 <div class="col-sm-12">
                     <label for="password" class="form-label">{{ __('password') }} <span class="text-danger">*</span></label>
                     <input type="password" name="password" class="form-control form--control" id="password" value="{{ old('password') }}">
                     @error('password')
                         <p class="text-danger pt-2">{{ $message }}</p>
                     @enderror


                     <label for="password-confirm" class="form-label">{{ __('confirm_password') }} <span class="text-danger">*</span></label>
                     <input type="password" name="password_confirmation" class="form-control form--control" id="password-confirm" value="{{ old('password') }}"   autocomplete="new-password" >
                     @error('password_confirmation')
                         <p class="text-danger pt-2">{{ $message }}</p>
                     @enderror

                 </div>



                 <div class="col-sm-12 mt-2">
                     <button class="btn w-100 btn-primary btn-lg" type="submit"> {{ __('reset_password') }}</button>
                 </div>

             </form>


        </div>
    </div>
</section>
<!-- Auth Login Section -->


@endsection
