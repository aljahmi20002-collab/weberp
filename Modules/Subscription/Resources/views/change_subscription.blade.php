@extends('backend.partials.master')
@section('title')
    {{ __('change_subscription') }} 
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('change_subscription') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('settings.tax.rate.index') }}">{{ __('change_subscription') }}</a> </li>
            <li>  {{ __('create') }} </li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden">  
                            <a href="{{ route('settings.tax.rate.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('subscription.change.post') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">

                                <div class="col-lg-6 mt-3">
                                    <label for="business_id" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                                     <select class="form-control form--control select2" name="business_id">
                                        <option disabled selected>{{ __('select') }} {{ __('business') }}</option>
                                        @foreach ($businesses as $business)
                                            <option value="{{ $business->id }}" @if(old('business_id') == $business->id) selected @endif>{{ $business->business_name }}</option> 
                                        @endforeach
                                     </select>
                                    @error('business_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-6 mt-3">
                                    <label for="plan_id" class="form-label">{{ __('plan') }} <span class="text-danger">*</span></label>
                                     <select class="form-control form--control select2" name="plan_id">
                                        <option disabled selected>{{ __('select') }} {{ __('plan') }}</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}" @if(old('plan_id') == $plan->id) selected @endif>{{ $plan->name }}</option> 
                                        @endforeach
                                     </select>
                                    @error('plan_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('change')}}</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
