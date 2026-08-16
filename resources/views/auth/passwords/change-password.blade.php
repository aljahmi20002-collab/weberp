@extends('backend.partials.master')
@section('title',__('change_password'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('dashboard') }}</h5>
        <ul class="breadcrumb">
            <li>
                <a href="{{ route('dashboard.index') }}">{{ __('home') }}</a>
            </li>
            <li>
                {{ __('change_password') }}
            </li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
             {{-- User Profile Edit   --}}
            <div class="dashboard--widget ">

                {{-- User Profile Forms Starts  --}}
                <form class="row gy-3" action="{{ route('update.password') }}" method="post">
                    @csrf
                    <div class="col-md-6 mt-5">

                        <h3 class="mb-3">{{ __('change_password') }}</h3>
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label" for="password">{{ __('current_password') }}</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form--control" id="password"
                                    placeholder="********" name="current_password" value="{{ old('current_password') }}" >
                                    <label class="edit-icon" for="password"><i class="fas fa-pen"></i></label>
                                    @error('current_password')
                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="new-password">{{ __('new_password') }}</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form--control" id="new-password"
                                       placeholder="********" name="new_password" value={{ old('new_password') }}>
                                    <label class="edit-icon" for="new-password"><i
                                            class="fas fa-pen"></i></label>
                                    @error('new_password')
                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="confirm-password">{{ __('confirm_password') }}</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form--control"
                                        id="confirm-password" placeholder="********" name="confirm_password" value={{ old('confirm_password') }} >
                                    <label class="edit-icon" for="confirm-password"><i
                                            class="fas fa-pen"></i></label>
                                        @error('confirm_password')
                                            <p class="mt-2 text-danger">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn submit-btn btn-primary btn-lg">{{__('save_changes')}}</button>
                    </div>
                </form>
                {{-- User Profile Forms Ends   --}}
            </div>
        </div>
    </div>
@endsection
