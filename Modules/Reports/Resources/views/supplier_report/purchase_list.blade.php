<div class="col-12"> 
    <p class="overflow-hidden text-center my-2" > <b>{{__('supplier_report') }} ({{ __('purchase_report') }}) </b></p>
</div>
<div class="col-lg-12 px-3 mb-3" style="padding-left:10px">  
    <div class="d-flex mt-3 align-items-center ">
        <div class="width-100px" >{{ __('date') }}:</div>
        <div >{{ $request->date }}</div>
    </div>   
    <div class="d-flex mt-3 align-items-center ">
        <div class="width-100px" >{{ __('supplier') }}:</div>
        <div >
            @if($supplier)
                {{ $supplier->name }}
            @else
                All
            @endif
        </div>
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
                @if(!$supplier)
                <th>{{ __('supplier') }}</th>   
                @endif
                <th>{{ __('total_purchase_cost') }}<br>(Inc.Tax)</th>     
                <th>{{ __('total_payments') }}<br/></th>  
                <th>{{ __('total_due') }} </th>   
            </tr>
        </thead>
        <tbody>
            @php
                $i=0;
                $total_purchase_cost  = 0;
                $total_purchase_paid  = 0;
                $total_purchase_due   = 0;
                $total_purchase_cost_exc_tax = 0;
                $total_purchase_tax          = 0;
            @endphp
            @foreach ($purchases as $purchase)
            @php 
                $total_purchase_cost         += $purchase->TotalPurchaseCost;
                $total_purchase_paid         += $purchase->payments->sum('amount');
                $total_purchase_due          += $purchase->DueAmount;
                $total_purchase_cost_exc_tax += $purchase->purchaseItems->sum('total_unit_cost');
                $total_purchase_tax          += $purchase->TotalTaxAmount;
            @endphp
            <tr> 
                <td data-title="#">{{ ++$i }}</td>  
                <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y H:i:s') }}</td> 
                <td data-title="{{ __('details') }}">
                    <span>{{ __('purchase_no') }}</span>:{{ @$purchase->purchase_no }} <br/>
                    <span class="mt-2">{{ __('purchase') }}:</span> {!! @$purchase->my_purchase_status !!} <br/>
                    <span class="mt-2">{{ __('payment') }}:</span>{!! @$purchase->my_payment_status !!}<br/>
                    @if($branchname == 'All')
                        <span class="mt-2">{{ __('branch') }}:</span>
                        @foreach ($purchase->PurchasedBranch as $branch)
                            <span class="badge badge-pill  badge-primary">{{ @$branch->name }}</span>
                        @endforeach 
                    @endif
                </td> 
                @if(!$supplier)
                <td data-title="{{ __('supplier') }}" class="purchase_supplier">
                    <div >
                        <span>{{ __('name') }}</span>    : {{ @$purchase->supplier->name }}
                    </div>
                    <div >
                        <span>{{ __('company') }}</span> : {{ @$purchase->supplier->company_name }}
                    </div>
                    <div >
                        <span>{{ __('phone') }}</span>  : {{ @$purchase->supplier->phone }}
                    </div> 
                    <div>
                        <span>{{ __('address') }}</span>  : <span class="address"> {{ @$purchase->supplier->address }}</span>
                    </div> 
                </td>  
                @endif  
                <td data-title="{{ __('total_purchase_cost') }}">
                    {{ @businessCurrency($purchase->business_id) }} {{ @number_format($purchase->total_purchase_cost,2) }}
                </td>  
                <td data-title="{{ __('total_payments') }}">
                    {{ @businessCurrency($purchase->business_id) }} {{ @number_format($purchase->payments->sum('amount'),2) }}
                </td>   
                <td data-title="{{ __('total_due') }}">
                    {{ @businessCurrency($purchase->business_id) }} {{ @number_format($purchase->DueAmount,2) }}
                </td>   
            </tr>
            @endforeach
            @if ($purchases->count() <= 0)
                <tr>
                    <td colspan="7" class="text-center">{{ __('purchase_data_was_not_found') }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_purchase_cost') }} (Exc.Tax):</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_purchase_cost_exc_tax,2) }}  </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_purchase_tax') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_purchase_tax,2) }}  </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_purchase_cost') }} (Inc.Tax):</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_purchase_cost,2) }}  </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_payments') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_purchase_paid,2) }}  </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-between">
                        <div class="font-weight-bold"> {{ __('total_due_amount') }}:</div>
                        <div class="font-weight-bold"> {{ businessCurrency(business_id()) }} {{ @number_format($total_purchase_due,2) }}  </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="d-flex flex-row-reverse align-items-center pagination-content">
    <span class="paginate pagination-number-link">{{ $purchases->links() }}</span>
    <p class="p-2 small paginate">
        {!! __('Showing') !!}
        <span class="font-medium">{{ $purchases->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-medium">{{ $purchases->lastItem() }}</span>
        {!! __('of') !!}
        <span class="font-medium">{{ $purchases->total() }}</span>
        {!! __('results') !!}
    </p>
</div>
<!-- Pagination -->  
<script src="{{static_asset('backend')}}/js/reports/supplier_report/supplier_report_pagination.js"></script> 