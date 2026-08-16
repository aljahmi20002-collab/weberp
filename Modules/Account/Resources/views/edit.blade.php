@extends('backend.partials.master')
@section('title')
    {{ __('account') }} {{ __('create') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('account') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('accounts') }}</a> </li>
            <li> <a href="{{ route('accounts.account.index') }}">{{ __('account') }}</a> </li>
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
                            <a href="{{ route('accounts.account.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('accounts.account.update',['id'=>$account->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3"> 
                                <div class="col-lg-6 mt-2">
                                    <label for="account_type" class="form-label">{{ __('payment_gateway') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="payment_gateway" id="payment_gateway"> 
                                        @foreach (\Config::get('pos_default.payment_gatway') as $key=>$gateway)
                                            <option value="{{ $key }}" @if(old('payment_gateway',$account->payment_gateway) == $key) selected @endif>{{ __($gateway) }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_gateway')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
   
                                <div class="col-lg-6 mt-3 bank @if(old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::BANK) d-block @else d-none @endif">
                                    <label for="bank_name" class="form-label">{{ __('bank_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" class="form-control form--control" id="bank_name" value="{{ old('bank_name',$account->bank_name) }}">
                                    @error('bank_name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-sm-6 mt-2 both @if(old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::BANK || old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::MOBILE) d-block @else d-none @endif">
                                    <label for="holder_name" class="form-label">{{ __('holder_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="holder_name" class="form-control form--control" id="holder_name" value="{{ old('holder_name',$account->holder_name) }}">
                                    @error('holder_name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-sm-6 mt-2 bank @if(old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::BANK) d-block @else d-none @endif">
                                    <label for="account_no" class="form-label">{{ __('account_no') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="account_no" class="form-control form--control" id="account_no" value="{{ old('account_no',$account->account_no) }}">
                                    @error('account_no')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                               
                                <div class="col-sm-6 mt-2 bank @if(old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::BANK) d-block @else d-none @endif">
                                    <label for="branch_name" class="form-label">{{ __('branch_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="branch_name" class="form-control form--control" id="branch_name" value="{{ old('branch_name',$account->branch_name) }}">
                                    @error('branch_name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 mt-2 mobile @if(old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::MOBILE) d-block @else d-none @endif">
                                    <label for="mobile" class="form-label">{{ __('mobile') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" class="form-control form--control" id="mobile" value="{{ old('mobile',$account->mobile) }}">
                                    @error('mobile')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 mt-2 mobile @if(old('payment_gateway',$account->payment_gateway) == \Modules\Account\Enums\PaymentGateway::MOBILE) d-block @else d-none @endif">
                                    <label for="number_type" class="form-label">{{ __('number_type') }} <small>(ex:{{ __('personal') }}/{{ __('merchant') }})</small> <span class="text-danger">*</span></label>
                                    <input type="text" name="number_type" class="form-control form--control" id="number_type" value="{{ old('number_type',$account->number_type) }}">
                                    @error('number_type')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if(business())
                                <div class="col-md-6 mt-2">
                                    <label for="balance" class="form-label">{{ __('balance') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="balance" class="form-control form--control" id="balance" value="{{ old('balance',$account->balance) }}">
                                    @error('balance')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                                <div class="col-6">
                                    <div class="row">  
                                        <div class="col-lg-6 mt-4 pt-lg-3">
                                            <div class="d-flex mt-2">
                                                <label class="form-label cmr-10">{{ __('status') }}</label>
                                                <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',$account->status) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                                <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                            </div>
                                        </div>
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
    <input type="hidden" value="{{ \Modules\Account\Enums\PaymentGateway::CASH }}" id="cash_enum"/>
    <input type="hidden" value="{{ \Modules\Account\Enums\PaymentGateway::BANK }}" id="bank_enum"/>
    <input type="hidden" value="{{ \Modules\Account\Enums\PaymentGateway::MOBILE }}" id="mobile_enum"/>
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/account/edit.js" ></script>  
@endpush