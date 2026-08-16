@extends('backend.partials.master')
@section('title')
    {{ __('business') }} {{ __('edit') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('business') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('business.index') }}">{{ __('business') }}</a> </li>
            <li>  {{ __('edit') }} </li>
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
                        <form action="{{ route('business.update',['id'=>$business->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
                                <div class="col-lg-6 mt-3">
                                    <label for="business_name" class="form-label">{{ __('business_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="business_name" class="form-control form--control" id="business_name" value="{{ old('business_name',$business->business_name) }}">
                                    @error('business_name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="start_date" class="form-label">{{ __('start_date') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="start_date" class="form-control form--control date" id="start_date" value="{{ old('start_date',$business->start_date) }}">
                                    @error('start_date')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="logo" class="form-label">{{ __('logo') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="logo" class="form-control form--control" id="logo" value="{{ old('logo') }}">
                                    @error('logo')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="symbol" class="form-label">{{ __('currency') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="currency">
                                        <option selected disabled>{{ __('select') }} {{ __('currency') }}</option>
                                        @foreach (currency() as $currency)
                                            <option value="{{ $currency->id }}" @if(old('currency',$business->currency_id) == $currency->id) selected @endif>{{ $currency->country }} ( {{ $currency->code }} )</option>
                                        @endforeach
                                     </select>
                                    @error('currency')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                
                                <div class="col-sm-6 mt-0">
                                    <label for="sku_prefix" class="form-label">{{ __('sku_prefix') }} </label>
                                    <input type="text" name="sku_prefix" class="form-control form--control" id="sku_prefix" value="{{ old('sku_prefix',$business->sku_prefix) }}">
                                    @error('sku_prefix')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                                 
                                <div class="col-lg-6  ">
                                    <label for="default_profit_percent" class="form-label">{{ __('default_profit_percent') }}  </label>
                                    <input type="text" name="default_profit_percent" class="form-control form--control" id="default_profit_percent" value="{{ old('default_profit_percent',$business->default_profit_percent) }}">
                                    @error('default_profit_percent')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-4 pt-lg-3">
                                    <div class="d-flex mt-3">
                                        <label class="form-label cmr-10">{{ __('status') }}</label>
                                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',$business->status) ==null? '':'checked' }} >
                                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
