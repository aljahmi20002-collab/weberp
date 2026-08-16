@extends('backend.partials.master')
@section('title')
    {{ __('payment_settings') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('payment_settings') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('settings') }}</a> </li>
            <li>  {{ __('payment_settings') }} </li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-4">
                    <div class="dashboard--widget  plan-card">  
                        <h5>{{ __('stripe') }}</h5>
                        <form action="{{ route('settings.payment.settings.update') }}" method="post" enctype="multipart/form-data" class="plan-card-2" >
                            @csrf
                            @method('put') 
                            <div class="plan-card-2"> 
                                <div class="row mt-3 align-items-center  "> 
                                    <div class="col-lg-12 pt-2">
                                        <label for="stripe_publishable_key" class="form-label">{{ __('publishable_key') }}</label>
                                        <input type="text" name="stripe_publishable_key" class="form-control form--control" id="stripe_publishable_key" value="{{ old('stripe_publishable_key',settings('stripe_publishable_key')) }}">
                                    </div>
                                    <div class="col-lg-12 pt-2">
                                        <label for="stripe_secret_key" class="form-label">{{ __('secret_key') }} </label>
                                        <input type="stripe_secret_key" name="stripe_secret_key" class="form-control form--control" id="stripe_secret_key" value="{{ old('stripe_secret_key',settings('stripe_secret_key')) }}">
                                    </div> 
                                    <div class="col-lg-6">
                                        <label for="stripe_status" class="form-label">{{ __('status') }} </label>
                                        <select class="form-control form--control select2" id="stripe_status" name="stripe_status">
                                            <option value="{{ \App\Enums\Status::ACTIVE }}" @if(settings('stripe_status') == \App\Enums\Status::ACTIVE)  selected @endif>{{ __('active') }}</option>
                                            <option value="{{ \App\Enums\Status::INACTIVE }}" @if(settings('stripe_status') == \App\Enums\Status::INACTIVE)  selected @endif>{{ __('inactive') }}</option>
                                        </select>
                                    </div> 
                                    <div class="col-lg-6 pt-4">
                                        @if(settings('stripe_status')  == \App\Enums\Status::ACTIVE)
                                            <span class="badge badge-success badge-pill"> {{ __('active') }}</span>
                                        @else
                                            <span class="badge badge-danger badge-pill"> {{ __('inactive') }}</span>
                                        @endif
                                    </div>   
                                </div>  
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save_changes')}}</button>
                            </div> 
                        </form>  
                    </div>
                </div>
 
                            
                <div class="col-xl-4">
                    <div class="dashboard--widget  plan-card">  
                        <h5>{{ __('paypal') }}</h5>
                        <form action="{{ route('settings.payment.settings.update') }}" method="post" enctype="multipart/form-data" class="plan-card-2">
                            @csrf
                            @method('put') 
                            <div class="plan-card-2">
                                <div class="row mt-3 align-items-center"> 
                                    <div class="col-lg-12 pt-2">
                                        <label for="paypal_client_id" class="form-label">{{ __('client_id') }}</label>
                                        <input type="text" name="paypal_client_id" class="form-control form--control" id="paypal_client_id" value="{{ old('paypal_client_id',settings('paypal_client_id')) }}">
                                    </div>
                                    <div class="col-lg-12 pt-2">
                                        <label for="paypal_client_secret" class="form-label">{{ __('secret_key') }} </label>
                                        <input type="paypal_client_secret" name="paypal_client_secret" class="form-control form--control" id="paypal_client_secret" value="{{ old('paypal_client_secret',settings('paypal_client_secret')) }}">
                                    </div> 
                                    <div class="col-lg-12 pt-2">
                                        <label for="paypal_mode" class="form-label">{{ __('mode') }} </label>
                                        <input type="paypal_mode" name="paypal_mode" class="form-control form--control" id="paypal_mode" value="{{ old('paypal_mode',settings('paypal_mode')) }}">
                                    </div> 
                                    <div class="col-lg-6">
                                        <label for="paypal_status" class="form-label">{{ __('status') }} </label>
                                         <select class="form-control form--control select2" id="paypal_status" name="paypal_status">
                                            <option value="{{ \App\Enums\Status::ACTIVE }}" @if(settings('paypal_status') == \App\Enums\Status::ACTIVE)  selected @endif>{{ __('active') }}</option>
                                            <option value="{{ \App\Enums\Status::INACTIVE }}" @if(settings('paypal_status') == \App\Enums\Status::INACTIVE)  selected @endif>{{ __('inactive') }}</option>
                                         </select>
                                    </div> 
                                    <div class="col-lg-6 pt-4">
                                        @if(settings('paypal_status')  == \App\Enums\Status::ACTIVE)
                                            <span class="badge badge-success badge-pill"> {{ __('active') }}</span>
                                        @else
                                            <span class="badge badge-danger badge-pill"> {{ __('inactive') }}</span>
                                        @endif
                                    </div>  
                                </div> 
                            </div>
                            <div class="col-md-12  text-end">
                                <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save_changes')}}</button>
                            </div> 
                        </form>  
                    </div>
                </div>




                <div class="col-xl-4">
                    <div class="dashboard--widget  plan-card">  
                        <h5>{{ __('skrill') }}</h5>
                        <form action="{{ route('settings.payment.settings.update') }}" method="post" enctype="multipart/form-data" class="plan-card-2">
                            @csrf
                            @method('put')  
                            <div class="plan-card-2">
                                <div class="row mt-3 align-items-center"> 
                                    <div class="col-lg-12 pt-2">
                                        <label for="skrill_merchant_email" class="form-label">{{ __('merchant_email') }}</label>
                                        <input type="text" name="skrill_merchant_email" class="form-control form--control" id="skrill_merchant_email" value="{{ old('skrill_merchant_email',settings('skrill_merchant_email')) }}">
                                    </div> 
                                    <div class="col-lg-6 pt-2">
                                        <label for="skrill_status" class="form-label">{{ __('status') }} </label>
                                         <select class="form-control form--control select2" id="skrill_status" name="skrill_status">
                                            <option value="{{ \App\Enums\Status::ACTIVE }}" @if(settings('skrill_status') == \App\Enums\Status::ACTIVE)  selected @endif>{{ __('active') }}</option>
                                            <option value="{{ \App\Enums\Status::INACTIVE }}" @if(settings('skrill_status') == \App\Enums\Status::INACTIVE)  selected @endif>{{ __('inactive') }}</option>
                                         </select>
                                    </div> 
                                    <div class="col-lg-6 pt-4">
                                        @if(settings('skrill_status')  == \App\Enums\Status::ACTIVE)
                                            <span class="badge badge-success badge-pill"> {{ __('active') }}</span>
                                        @else
                                            <span class="badge badge-danger badge-pill"> {{ __('inactive') }}</span>
                                        @endif
                                    </div> 
    
                                </div> 
                            </div>
                            <div class="col-md-12  text-end">
                                <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save_changes')}}</button>
                            </div> 
                        </form>  
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
 