<div class="tab-pane fade @if(!$request->supplier_invoice && !$request->supplier_invoice && !$request->purchase_payment && !$request->return_invoice && !$request->return_payment) show active @endif" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="row g-4 customer-profile"> 
        <div class="col-xl-4">
            <div class="dashboard--widget height100"> 
                <h5>{{ __('supplier_profile') }}</h5>
                <div class="d-flex align-items-center mt-3">
                    <div >
                        <img class="rounded-circle mr-20" src="{{ @$supplier->image }}" width="50"/>
                    </div>
                    <div class="line-height-2">
                        {{ @$supplier->name }} <br/>
                        {{ @$supplier->email }} 
                    </div>
                </div>
            </div>
        </div>  
        <div class="col-xl-8">
            <div class="dashboard--widget height100">
                <h5>{{ __('supplier_info') }}</h5>
                <div class="row mt-3">
                    
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0">{{ __('company') }}:</p>
                            <p>{{ @$supplier->company_name }} </p>
                        </div>
                    </div>  
                   
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('phone') }}:</p>
                            <p>{{ $supplier->phone }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('address') }}:</p>
                            <p>{{ $supplier->address }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0  ">{{ __('opening_balance') }}:</p>
                            <p> {{ businessCurrency(business_id()) }} {{ number_format($supplier->opening_balance,2) }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('balance') }}:</p>
                            <p>{{ businessCurrency(business_id()) }} {{ number_format($supplier->balance,2) }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('status') }}:</p>
                            <p>{!! $supplier->MyStatus !!}</p>
                        </div>
                    </div> 
                </div>
            </div>
        </div>  
       
    </div>
    <div class="row "> 
        <div class="col-12 mt-4">
            <h5>{{ __('invoice') }}</h5>
        </div>
        <div class="col-xl-4 mt-3"> 
            <div class="dashboard--widget"> 
                <div class="chart-area">
                    <div id="supplier_purchase_pie_chart" class="chart"></div>
                </div> 
            </div> 
        </div>
        <div class="col-xl-8">
            <div class="row height100"> 
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100"> 
                        <div class="d-flex align-items-center height100">
                            <div>
                               <i class="fa fa-file fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                 <span>{{ __('total_invoice') }}</span> <br/>
                                 <span class="fontsize20px"> {{ @$purchaseTotal['total_purchase_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100"> 
                        <div class="d-flex align-items-center height100">
                            <div>
                               <i class="fa fa-file-invoice-dollar fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                 <span>{{ __('total_amount') }}</span> <br/>
                                 <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ number_format($purchaseTotal['total_amount'],2) }} </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100">
                        <div class="d-flex align-items-center height100">
                            <div  >
                               <i class="fa fa-hand-holding-dollar fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                 <span>{{ __('total_payments') }}</span>  <br/>
                                 <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ @number_format($purchaseTotal['total_payments'],2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-3">
                    <div class="dashboard--widget height100 "> 
                        <div class="d-flex align-items-center height100">
                            <div  >
                               <i class="fa fa-dollar-sign fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                <span>{{ __('total_due') }}</span> <br/>
                                <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ @number_format($purchaseTotal['total_due'],2) }}</span>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div>
    <div class="row "> 
        <div class="col-12 mt-4">
            <h5>{{ __('return_invoice') }}</h5>
        </div>
        <div class="col-xl-4 mt-3"> 
            <div class="dashboard--widget"> 
                <div class="chart-area">
                    <div id="supplier_purchase_return_pie_chart" class="chart"></div>
                </div> 
            </div> 
        </div>
        <div class="col-xl-8">
            <div class="row height100"> 
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100"> 
                        <div class="d-flex align-items-center height100">
                            <div>
                               <i class="fa fa-file fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                 <span>{{ __('total_invoice') }}</span> <br/>
                                 <span class="fontsize20px"> {{ @$purchaseReturnTotal['total_purchase_return_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100"> 
                        <div class="d-flex align-items-center height100">
                            <div>
                               <i class="fa fa-file-invoice-dollar fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                 <span>{{ __('total_amount') }}</span> <br/>
                                 <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ number_format($purchaseReturnTotal['total_amount'],2) }} </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6  mt-3">
                    <div class="dashboard--widget height100">
                        <div class="d-flex align-items-center height100">
                            <div  >
                               <i class="fa fa-hand-holding-dollar fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                 <span>{{ __('total_payments') }}</span>  <br/>
                                 <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ @number_format($purchaseReturnTotal['total_payments'],2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-3">
                    <div class="dashboard--widget height100 "> 
                        <div class="d-flex align-items-center height100">
                            <div  >
                               <i class="fa fa-dollar-sign fontsize35px mr-20"></i>
                            </div>
                            <div class="line-height-2">
                                <span>{{ __('total_due') }}</span> <br/>
                                <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ @number_format($purchaseReturnTotal['total_due'],2) }}</span>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div>
 
</div>

@push('scripts')
    @include('supplier::view-content.chart_js')
@endpush    