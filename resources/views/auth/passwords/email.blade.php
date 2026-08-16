
@extends('layouts.app')
@section('title', __('reset_password'))
@section('maincontent')

  <!-- Auth Login Section -->
  <section class="auth-section">
      <div class="auth-content "> 
        <div class="text-center mb-5">
            <img src="{{ settings('logo') }}"/>
        </div>

          <h3 class="title">{{ __('reset_password') }}</h3>
          <div class="row gy-4">

            <div class="col-12">
                @if(session('status'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">{{ session('status') }}</div>
                @endif 
               <form action="{{ route('password.reset.link') }}" method="post">
                   @csrf
                   <div class="col-sm-12">
                       <label for="email" class="form-label">{{ __('email') }} <span class="text-danger">*</span></label>
                       <input type="text" name="email" class="form-control form--control" id="email" value="{{ old('email') }}">
                       @error('email')
                           <p class="text-danger pt-2">{{ $message }}</p>
                       @enderror
                   </div>

                   <div class="col-sm-12 mt-2">
                       <button class="btn w-100 btn-primary btn-lg" type="submit">  {{ __('send_password_reset_link') }}</button>
                   </div>

               </form>

            </div>

          </div>
      </div>
  </section>
  <!-- Auth Login Section -->

@endsection

