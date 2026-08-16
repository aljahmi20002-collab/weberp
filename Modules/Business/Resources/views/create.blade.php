@extends('backend.partials.master')
@section('title')
    {{ __('business') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('business') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('business.index') }}">{{ __('business') }}</a> </li>
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

                            <a href="{{ route('business.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('business.store') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                            <div class="row mt-3">
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

                                <div class="col-sm-6 mt-0">
                                    <label for="sku_prefix" class="form-label">{{ __('sku_prefix') }} </label>  
                                    <input type="text" name="sku_prefix" class="form-control form--control" id="sku_prefix" value="{{ old('sku_prefix') }}">
                                    @error('sku_prefix')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                                    
                                <div class="col-lg-6  ">
                                    <label for="default_profit_percent" class="form-label">{{ __('default_profit_percent') }}  </label>
                                    <input type="text" name="default_profit_percent" class="form-control form--control" id="default_profit_percent" value="{{ old('default_profit_percent') }}">
                                    @error('default_profit_percent')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
 
                                
            
                                <h6 class="title my-3">{{ __('owner_information') }}</h6>
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

                                <div class="col-lg-6 mt-4 pt-lg-3">
                                    <div class="d-flex mt-3">
                                        <label class="form-label cmr-10">{{ __('status') }}</label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{static_asset('backend/assets')}}/js/date.js"></script>
@endpush