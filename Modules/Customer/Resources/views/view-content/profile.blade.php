<div class="tab-pane fade @if(!$request->customer_invoice && !$request->customer_payments) show active @endif" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="row g-4 customer-profile"> 
        <div class="col-xl-3">
            <div class="dashboard--widget height100"> 
                <div class="d-flex align-items-center">
                    <div >
                        <img class="rounded-circle mr-20" src="{{ @$customer->image }}" width="50"/>
                    </div>
                    <div class="line-height-2">
                        {{ @$customer->name }} <br/>
                        {{ @$customer->email }} 
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-xl-3">
            <div class="dashboard--widget height100"> 
                <div class="d-flex align-items-center">
                    <div>
                       <i class="fa fa-file-invoice-dollar fontsize35px mr-20"></i>
                    </div>
                    <div class="line-height-2">
                         <span>{{ __('total_invoice') }}</span> <br/>
                         <span class="fontsize20px">{{ businessCurrency(business_id()) }} {{ number_format($totalSale['total_amount'],2) }} ({{ @$customer->totalPurchaseFromSales->count() }})</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="dashboard--widget height100">
                <div class="d-flex align-items-center">
                    <div  >
                       <i class="fa fa-hand-holding-dollar fontsize35px mr-20"></i>
                    </div>
                    <div class="line-height-2">
                         <span>{{ __('total_payments') }}</span>  <br/>
                         <span class="fontsize20px">{{ businessCurrency(business_id()) }}{{ @number_format($totalSale['total_payment'],2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="dashboard--widget height100 "> 
                <div class="d-flex align-items-center">
                    <div  >
                       <i class="fa fa-circle-dollar-to-slot fontsize35px mr-20"></i>
                    </div>
                    <div class="line-height-2">
                        <span>{{ __('total_due') }}</span> <br/>
                        <span class="fontsize20px">{{ businessCurrency(business_id()) }}{{ @number_format($totalSale['total_due'],2) }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <div class="row mt-3">
        <div class="col-xl-6">
            <div class="dashboard--widget height100">
                <h5>{{ __('customer_info') }}</h5>
                <div class="row mt-3">
                    
                    <div class="col-xl-6 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('phone') }}:</p>
                            <p>{{ $customer->phone }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-6 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('address') }}:</p>
                            <p>{{ $customer->address }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-6 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0  ">{{ __('opening_balance') }}:</p>
                            <p>{{ businessCurrency(business_id()) }} {{ number_format($customer->opening_balance,2) }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-6 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('balance') }}:</p>
                            <p>{{ businessCurrency(business_id()) }} {{ number_format($customer->balance,2) }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-6 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('status') }}:</p>
                            <p>{!! $customer->MyStatus !!}</p>
                        </div>
                    </div> 
                </div>
            </div>
        </div> 
        <div class="col-xl-6">
            <div class="dashboard--widget"> 
                <h5>{{ __('invoice') }}</h5>
                <div class="chart-area">
                    <div id="customer_pie_chart" class="chart"></div>
                </div> 
            </div>
        </div> 
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
          var options = {
                        chart: {
                            type: 'donut'
                        },
                      
                        series: [{{ $totalSale['total_payment'] }}, {{ $totalSale['total_due'] }}],
                        labels: ['{{ __("total_payments") }}', '{{ __("total_due") }}'],
                        colors: ['#28a745', '#ff1752'],  
                        plotOptions: {
                            pie: {
                                donut: {
                                        labels: {
                                            show: true,
                                            name: { 
                                              show:true
                                            },
                                            value:{ 
                                                show:true
                                            }
                                        } 
                                    }
                            }
                        } 
                    }
        var chart = new ApexCharts(document.querySelector("#customer_pie_chart"), options);
        chart.render();
    </script>
@endpush    