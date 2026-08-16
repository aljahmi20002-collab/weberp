
@foreach ($accounts as  $account)
    <option value="{{ $account->id }}" @if(old('from_account') == $account->id) selected @endif> 
        {{ __(\Config::get('pos_default.payment_gatway.'.$account->payment_gateway))}} |
        @if($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK)
            {{ @$account->bank_name }} | {{ @$account->holder_name }} | {{ @$account->account_no }} | {{ @$account->branch_name }} || {{ @$account->balance }} 
        @elseif($account->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE) 
            {{ @$account->holder_name }} | {{ @$account->mobile }} | {{ @$account->number_type }} || {{ businessCurrency(business_id()) }} {{ @$account->balance }} 
        @endif 
    </option>
@endforeach