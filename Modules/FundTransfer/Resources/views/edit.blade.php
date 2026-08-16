@extends('backend.partials.master')
@section('title')
    {{ __('fund_transfer') }} {{ __('edit') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('fund_transfer') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('accounts') }}</a> </li>
            <li> <a href="{{ route('accounts.fund.transfer.index') }}">{{ __('fund_transfer') }}</a> </li>
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
                            <a href="{{ route('accounts.fund.transfer.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('accounts.fund.transfer.update',['id'=>$fund_transfer->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
  
                                <div class="col-lg-6 mt-2">
                                    <label for="from_account" class="form-label">{{ __('from_account') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="from_account" id="from_account"> 
                                        @foreach ($accounts as  $account)
                                            <option value="{{ $account->id }}" @if(old('from_account',$fund_transfer->from_account) == $account->id) selected @endif> 
                                                 {{ __(\Config::get('pos_default.payment_gatway.'.$account->payment_gateway))}} |
                                                @if($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK)
                                                    {{ @$account->bank_name }} | {{ @$account->holder_name }} | {{ @$account->account_no }} | {{ @$account->branch_name }} |
                                                @elseif($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE) 
                                                     {{ @$account->holder_name }} | {{ @$account->mobile }} | {{ @$account->number_type }} | 
                                                @endif
                                                {{ __('balance') }}: {{ businessCurrency(business_id()) }}{{ @$account->balance }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_account')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label for="to_account" class="form-label">{{ __('to_account') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="to_account" id="to_account"> 
                                        @foreach ($accounts as  $account)
                                            <option value="{{ $account->id }}" @if(old('to_account',$fund_transfer->to_account) == $account->id) selected @endif> 
                                                 {{ __(\Config::get('pos_default.payment_gatway.'.$account->payment_gateway))}} |
                                                @if($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK)
                                                    {{ @$account->bank_name }} | {{ @$account->holder_name }} | {{ @$account->account_no }} | {{ @$account->branch_name }} |
                                                @elseif($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE) 
                                                     {{ @$account->holder_name }} | {{ @$account->mobile }} | {{ @$account->number_type }} | 
                                                @endif
                                                {{ __('balance') }}: {{ businessCurrency(business_id()) }}{{ @$account->balance }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_account')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                <div class="col-md-6 mt-2">
                                    <label for="balance" class="form-label">{{ __('amount') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" class="form-control form--control" id="balance" value="{{ old('balance',$fund_transfer->amount) }}">
                                    @error('balance')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 

                                <div class="col-md-6 mt-2">
                                    <label for="balance" class="form-label">{{ __('description') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control form--control" name="description">{{ old('description',$fund_transfer->description) }}</textarea> 
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
 