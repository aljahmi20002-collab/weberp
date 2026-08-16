@extends('backend.partials.master')
@section('title',__('profile'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('profile') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('dashboard.index') }}">{{ __('home') }}</a> </li>
            <li>  {{ __('profile') }} </li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <!-- User Profile Edit -->
            <div class="dashboard--widget ">
                <!-- User Profile Top Starts -->
                <div class="user--profile mb-5">
                    <div class="thumb">
                        <img src="{{ Auth::user()->image }}" alt="clients">
                    </div>

                    <div class="content">
                        <div>
                            <h6 class="title"> {{ Auth::user()->name }} </h6>
                            <span class="d-block"> {{ Auth::user()->email }}</span>
                        </div>

                    </div>
                </div>

                <div class="row">
                    @include('backend.profile.profile_info')
                    <!-- Tabs UI Starts -->
                    <div class="col-lg-8 ">
                        @include('backend.profile.profile_navbar')
                        <div class="dashboard--widget">
                            <div class="tab-content">
                                  @include('backend.profile.profile_update.profile_change')
                                  @include('backend.profile.profile_update.account_change')
                                  @include('backend.profile.profile_update.avatar_change')
                                  @include('backend.profile.profile_update.password_change')
                            </div>
                        </div>
                    </div>
                    <!-- Tabs UI Ends -->

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var year = '1901:'+new Date().getFullYear();
            $('.dateofbirth').datepicker({
                changeMonth:true,
                dateFormat: 'dd/mm/yy',
                yearRange: year,
                changeYear:true,
            });
        });
    </script>
    <script src="{{static_asset('backend/assets')}}/js/profile.js"></script>
@endpush

