@extends('backend.partials.master')
@section('title')
    {{ __('expense') }} {{ __('edit') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('expense') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('accounts') }}</a> </li>
            <li> <a href="{{ route('accounts.expense.index') }}">{{ __('expense') }}</a> </li>
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

                            <a href="{{ route('accounts.expense.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('accounts.expense.update',['id'=>$expense->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row mt-3">  
                                <div class="col-lg-6 mt-2">
                                    <label for="account_head" class="form-label">{{ __('account_head') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="account_head" id="account_head"  > 
                                        <option selected disabled>{{ __('select') }} {{ __('account_head') }}</option>
                                        @foreach ($accountHeads as $head)
                                            <option value="{{ $head->id }}" @if(old('account_head',$expense->account_head_id) == $head->id) selected @endif>{{ @$head->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_head')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                             
                                <div class="col-lg-6 mt-2">
                                    <label for="from_account" class="form-label">{{ __('from_account') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="from_account" id="from_account">
                                        @foreach ($businessAccounts as  $account)
                                            <option value="{{ $account->id }}" @if(old('from_account',$expense->from_account) == $account->id) selected @endif> 
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
                                    <label for="to_branch" class="form-label">{{ __('to_branch') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="to_branch" id="to_branch" data-url="{{ route('accounts.expense.branch.account') }}"> 
                                        <option selected disabled>{{ __('select') }} {{ __('branch') }}</option>
                                        @foreach ($branches as  $branch)
                                            <option value="{{ $branch->id }}" @if(old('to_branch',$expense->branch_id) == $branch->id) selected @endif>{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('to_branch')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label for="to_account" class="form-label">{{ __('to_account') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="to_account" id="to_account">  
                                        @foreach ($branchAccounts as  $bAccount)
                                            <option value="{{ $bAccount->id }}" @if(old('from_account',$expense->from_account) == $bAccount->id) selected @endif> 
                                                {{ __(\Config::get('pos_default.payment_gatway.'.$bAccount->payment_gateway))}} 
                                                @if($bAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK)
                                                    {{ @$bAccount->bank_name }} | {{ @$bAccount->holder_name }} | {{ @$bAccount->account_no }}   {{ @$bAccount->branch_name }} 
                                                @elseif($bAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE) 
                                                    {{ @$bAccount->holder_name }} | {{ @$bAccount->mobile }} | {{ @$bAccount->number_type }}  
                                                @endif 
                                            </option>
                                        @endforeach 
                                    </select>
                                    @error('to_account')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                <div class="col-md-6 mt-2">
                                    <label for="amount" class="form-label">{{ __('amount') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" class="form-control form--control" id="amount" value="{{ old('amount',$expense->amount) }}">
                                    @error('amount')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                <div class="col-md-6 mt-2">
                                    <label for="document" class="form-label">{{ __('document') }}  </label>
                                    <input type="file" name="document" class="form-control form--control" > 
                                    @error('document')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
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
 
@push('scripts')
<script src="{{ static_asset('backend') }}/js/expense/edit.js" ></script> 
@endpush