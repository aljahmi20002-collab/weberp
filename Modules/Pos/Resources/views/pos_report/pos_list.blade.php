

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
<div class="report_print_table" >
    <table class="table table-striped table-hover text-left">
        <thead>
            <tr class="border-bottom">
                <th>#</th>    
                <th>{{ __('date') }}</th>  
                <th>{{ __('details') }}</th>  
                <th>{{ __('customer_details') }}</th>   
                <th>{{ __('status') }}</th>   
                <th>{{ __('total_sell_price') }}</th>     
                <th>{{ __('total_payments') }}</th>     
                <th>{{ __('total_due') }}</th>     
            </tr>
        </thead>
        <tbody>
            @php
                $i=0; 
                $total_selling_price = 0;
                $total_payments      = 0;
                $total_due           = 0;
            @endphp
 
            @foreach ($poses as $pos)    
            @php 
                $total_selling_price += $pos->total_sale_price;
                $total_payments      += $pos->payments->sum('amount');
                $total_due           += $pos->DueAmount;
            @endphp                                    
            <tr> 
                <td data-title="#">{{ ++$i }}</td>  
                <td data-title="{{ __('date') }}"><small>{{ \Carbon\Carbon::parse($pos->created_at)->format('d-m-Y') }}</small></td> 
                <td data-title="{{ __('details') }}">
                    <span>{{ __('invoice_no') }}</span>:{{ @$pos->invoice_no }}<br/>
                    @if(!isUser())
                        <span>{{ __('branch') }}</span>:{{ @$pos->branch->name }}
                    @endif
                </td> 
            
                <td data-title="{{ __('customer') }}" class="purchase_supplier">
                    <div class=" "> 
                        <small>{{ __('type') }}: {{ __(\Config::get('pos_default.customer_type.'.@$pos->customer_type))}}</small>
                    </div>
                    @if($pos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
                    <div > 
                        <small>{{ __('name') }} : {{ @$pos->customer->name }}</small>
                    </div>
                    <div >
                        <small>{{ __('email') }} : {{ @$pos->customer->email }}</small>
                    </div>
                    <div >
                        <small>{{ __('phone') }}: {{ @$pos->customer->phone }}</small>  
                    </div> 
                    <div >
                        <small>{{ __('address') }}:{{ @$pos->customer->address }}</small>
                    </div> 
                    @endif
                </td> 
                <td data-title="{{ __('status') }}">
                    <small>{{ __('payment') }}: {!! @$pos->my_payment_status !!}</small><br/>
                    <small>{{ __('shipping') }}: {{ __(\Config::get('pos_default.shpping_status.'.$pos->shipping_status)) }}</small>
                </td>      
                <td data-title="{{ __('total_sell_price') }}">
                    {{ @businessCurrency($pos->business_id) }} {{ @number_format($pos->total_sale_price,2) }}
                </td>    
                <td data-title="{{ __('total_payments') }}">
                    {{ @businessCurrency($pos->business_id) }} {{ @number_format($pos->payments->sum('amount'),2) }}
                </td>    
                <td data-title="{{ __('total_due') }}">
                    {{ @businessCurrency($pos->business_id) }} {{ @number_format($pos->DueAmount,2) }}
                </td>    
            </tr>
            @endforeach
 
            @if ($poses->count() <= 0)
                <tr>
                    <td colspan="8" class="text-center">{{ __('pos_was_not_found') }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="8">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_selling_price') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_selling_price,2) }}  </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_payments') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_payments,2) }}  </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_due') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_due,2) }}  </div>
                    </div>
                </td>
            </tr> 
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="d-flex flex-row-reverse align-items-center pagination-content">
    <span class="paginate pagination-number-link">{{ $poses->links() }}</span>
    <p class="p-2 small paginate">
        {!! __('Showing') !!}
        <span class="font-medium">{{ $poses->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-medium">{{ $poses->lastItem() }}</span>
        {!! __('of') !!}
        <span class="font-medium">{{ $poses->total() }}</span>
        {!! __('results') !!}
    </p>
</div>
<!-- Pagination -->  
<script src="{{static_asset('backend')}}/js/pos/pos_report/pos_report_pagination.js"></script> 

 
