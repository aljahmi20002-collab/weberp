 @extends('layouts.app')
 @section('title',__('login'))

 @section('maincontent')
   <!-- Auth Login Section -->
   <section class="auth-section">
       <div class="auth-content ">
        <div class="text-center mb-5">
            <img src="{{ settings('logo') }}"/>
        </div>
           {{-- <h3 class="title">{{ __('sign_in') }}</h3> --}}
           <div class="row gy-4">
            <div class="col-sm-12 mt-2">
                @if(session('status'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">{{ session('status') }}</div>
                @endif
            </div>
                <form action="{{ route('login') }}" method="post" style="margin-top: 0px">
                    @csrf
                    <div class="col-sm-12">
                        <label for="email" class="form-label">{{ __('email') }}</label>
                        <input type="text" name="email" class="form-control form--control" id="email"     @if(Cookie::has('useremail')) ? value="{{Cookie::get('useremail')}}" : value="{{ old('email') }}" @endif>
                        @error('email')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-sm-12">
                        <label for="password" class="form-label">{{ __('password') }}</label>
                        <input type="password" name="password" class="form-control form--control" id="password"    @if(Cookie::has('userpassword')) value="{{Cookie::get('userpassword')}}" @endif>
                        @error('password')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    @if(settings('recaptcha_status') && settings('recaptcha_status') == 1)
                        <div class="col-sm-12 mt-2">
                                <div class="g-recaptcha" data-sitekey="{{ settings('recaptcha_site_key') }}"></div>
                                @error('g-recaptcha-response')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                @enderror
                        </div>
                    @endif
                    <div class="col-sm-12">
                        <div class="d-flex flex-wrap justify-content-between mt-2">
                            <div class="form-check">
                                <input type="checkbox" id="remember" name="remember" class="form-check-input" @if(old('remember')) checked @endif value="1">
                                <label for="remember"  class="form-check-label">{{ __('remember_me') }}</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text--base">{{ __('forgot_password') }}?</a>
                        </div>
                    </div>

                    <div class="col-sm-12 mt-2">
                        <button class="btn w-100 btn-primary btn-sm" type="submit">{{ __('sign_in') }}</button>
                    </div>
                </form>

                @if(env('DEMO'))
                <div class="col-sm-12 mt-2">
                    <div class="row">
                        @foreach (demoUsers() as $user)
                                <div class="col-sm-6">
                                    <a class="btn w-100 btn-primary mt-2 btn-lg" href="{{ route('demo.login',['email'=>$user->email])}}">{{ $user->name }}</a>
                                </div>
                        @endforeach
                    </div>
                </div>
                @endif

               <div class="col-sm-12 text-center">
                   <div class="mb-3 mt-4">
                       {{ __('don_t_have_an_account') }}? <a href="{{ route('register') }}" class="text-base">{{ __('sign_up') }}</a>
                   </div>
                   @if(
                        settings('facebook_status') == 1 ||
                        settings('google_status')   == 1
                   )
                   <div>
                       <div class="or">
                           <span>Or</span>
                       </div>
                       <div class="text-center">
                           <ul class="other-auth-options">
                            @if(Settings('facebook_status') == 1)
                               <li>
                                   <a href="{{ route('social.login',['facebook']) }}" class="facebook">
                                       <i class="fab fa-facebook-f"></i> 
                                   </a>
                               </li>
                               @endif
                               @if(Settings('google_status')   == 1)
                               <li>
                                   <a href="{{ route('social.login',['google']) }}" class="google">
                                       <i class="fab fa-google"></i>
                                   </a>
                               </li>
                               @endif
                           </ul>
                       </div>
                   </div>
                   @endif

               </div>
           </div>
       </div>
   </section>
   <!-- Auth Login Section -->

@endsection
