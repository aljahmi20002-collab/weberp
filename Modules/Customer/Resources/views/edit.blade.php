@extends('backend.partials.master')
@section('title')
    {{ __('customer') }} {{ __('edit') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('customers') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('customers.index') }}">{{ __('customers') }}</a> </li>
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

                            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('customers.update',['id'=>$customer->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row"> 
                                @if(isSuperadmin())
                                    <div class="col-6">
                                        <label for="business" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                                        <select class=" form-control form--control select2" name="business_id" id="business_id"  >
                                            <option  disabled selected>{{ __('select') }} {{ __('business') }}</option> 
                                                @foreach ($businesses as $business )
                                                    <option  value="{{ $business->id }}" @if(old('business_id',$customer->business_id) == $business->id) selected @endif>{{  @$business->business_name }}</option>
                                                @endforeach 
                                        </select>
                                        @error('business_id')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif 
 
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name',$customer->name) }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                                <div class="col-lg-6 mt-3">
                                    <label for="phone" class="form-label">{{ __('phone') }}  <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control form--control" id="phone" value="{{ old('phone',$customer->phone) }}">
                                    @error('phone')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>   
                                <div class="col-lg-6 mt-3">
                                    <label for="email" class="form-label">{{ __('email') }}  </label>
                                    <input type="email" name="email" class="form-control form--control" id="email" value="{{ old('email',$customer->email) }}">
                                    @error('email')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>   

                                
                                <div class="col-lg-6 mt-3">
                                    <label for="image" class="form-label">{{ __('image') }}  </label>
                                    <input type="file" name="image" class="form-control form--control" id="image" value="{{ old('image') }}">
                                    @error('image')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>   

                                
 
                                <div class="col-lg-3  mt-3">
                                    <label for="opening_balance" class="form-label">{{ __('opening_balance') }}  </label>
                                    <input type="text" name="opening_balance" class="form-control form--control" id="opening_balance" value="{{ old('opening_balance',$customer->opening_balance) }}">
                                    @error('opening_balance')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>    
                                
                                <div class="col-lg-3   mt-4 pt-lg-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex mt-3">
                                                <label class="form-label cmr-10">{{ __('status') }}</label>
                                                <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',$customer->status) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                                <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label for="address" class="form-label">{{ __('address') }} </label>
                                    <textarea   name="address" class="form-control form--control" id="address"  >{{ old('address',$customer->address) }}</textarea>
                                    @error('address')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-12 mt-5  text-end">
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
 