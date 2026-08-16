<div class="col-lg-12 px-3 mb-3" style="padding-left:10px"> 
    <div class="d-flex mt-3 align-items-center ">
        <div class="width-100px" >{{ __('date') }}:</div>
        <div >{{ $request->date }}</div>
    </div>  
    
    @if(!isUser() && $branchname)
        <div class="d-flex mt-3 align-items-center ">
            <div class="width-100px" >{{ __('branch') }}:</div>
            <div >{{ $branchname }}</div>
        </div>  
    @else
        <div class="d-flex mt-3 align-items-center ">
            <div class="width-100px" >{{ __('branch') }}:</div>
            <div >{{ @Auth::user()->branch->name }}</div>
        </div>  
    @endif
</div> 
<div class=" " >
    <table class="table table-striped table-hover text-left">
        <thead>
            <tr class="border-bottom">
                <th>#</th>
                <th>{{ __('from_account') }}</th> 
                <th>{{ __('to_account') }}</th>
                <th>{{ __('note') }}</th> 
                <th>{{ __('amount') }}</th> 

            </tr>
        </thead>
        <tbody>
            @php
                $i=0;
                
            @endphp
            @foreach ($expenses as $expense)
                <tr>
                    <td data-title="#">{{ ++$i }}</td>
                    <td data-title="{{ __('from_account') }}" class="account_details"> 
                        <div class="d-flex">
                            <span>{{ __('gateway') }}</span>:   {{ __(\Config::get('pos_default.payment_gatway.'.$expense->toAccount->payment_gateway))}}
                        </div>
                        @if($expense->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK)
                            <div class="d-flex">
                                <span>{{ __('bank') }}</span>: {{ @$expense->fromAccount->bank_name }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('holder') }}</span>: {{ @$expense->fromAccount->holder_name }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('acc_no') }}</span>: {{ @$expense->fromAccount->account_no }}
                            </div> 
                        @elseif($expense->fromAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE)
                            <div class="d-flex">
                                <span>{{ __('holder') }}</span>: {{ @$expense->fromAccount->holder_name }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('mobile') }}</span>: {{ @$expense->fromAccount->mobile }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('type') }}</span>: {{ @$expense->fromAccount->number_type }}
                            </div> 
                        @endif 
                    </td>   
                    <td data-title="{{ __('to_account') }}" class="account_details">
                        <div class="d-flex">
                            <span>{{ __('gateway') }}</span>:   {{ __(\Config::get('pos_default.payment_gatway.'.$expense->toAccount->payment_gateway))}}
                        </div>
                        @if($expense->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::BANK)
                            <div class="d-flex">
                                <span>{{ __('bank') }}</span>: {{ @$expense->toAccount->bank_name }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('holder') }}</span>: {{ @$expense->toAccount->holder_name }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('acc_no') }}</span>: {{ @$expense->toAccount->account_no }}
                            </div> 
                        @elseif($expense->toAccount->payment_gateway == \Modules\Account\Enums\PaymentGateway::MOBILE)
                            <div class="d-flex">
                                <span>{{ __('holder') }}</span>: {{ @$expense->toAccount->holder_name }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('mobile') }}</span>: {{ @$expense->toAccount->mobile }}
                            </div>
                            <div class="d-flex">
                                <span>{{ __('type') }}</span>: {{ @$expense->toAccount->number_type }}
                            </div> 
                        @endif   
                    </td> 
                    <td data-title="{{ __('note') }}"> {{ $expense->note }}</td> 
                    <td data-title="{{ __('amount') }}" class="text-right"> {{ businessCurrency(business_id()) }} {{ @$expense->amount }}</td> 
                </tr>
            @endforeach
            @if ($expenses->count() <= 0)
                <tr >
                    <td colspan="5" class="text-center">{{ __('expense_data_was_not_found') }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="5">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_amount') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($expenses->sum('amount'),2) }}  </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="d-flex flex-row-reverse align-items-center pagination-content">
    <span class="paginate pagination-number-link">{{ $expenses->appends($request->all())->links() }}</span>
    <p class="p-2 small paginate">
        {!! __('Showing') !!}
        <span class="font-medium">{{ $expenses->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-medium">{{ $expenses->lastItem() }}</span>
        {!! __('of') !!}
        <span class="font-medium">{{ $expenses->total() }}</span>
        {!! __('results') !!}
    </p>
</div>
<!-- Pagination --> 
<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script> 
<script src="{{static_asset('backend')}}/js/reports/expense_report_pagination.js"></script> 
 