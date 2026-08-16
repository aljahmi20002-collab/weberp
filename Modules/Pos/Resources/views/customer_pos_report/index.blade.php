@extends('backend.partials.master')
@section('title',__('customer_pos_report'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('reports') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('reports') }}</a> </li> 
            <li class="active">  {{ __('customer_pos_report') }} </li>
        </ul>
    </div>
@endsection

@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget "> 
                        <div class="row align-items-center"> 
                            <div class="col-lg-3 mt-3">
                                <label for="date" class="form-label">{{ __('date') }} <span class="text-danger">*</span></label>
                                <input type="text" name="date" class="form-control form--control date_range_picker"  value="{{ old('date',$request->date) }}" readonly id="date">
                            </div>
                            @if(!isUser())
                                <div class="col-lg-3 mt-3">
                                    <label for="branch_id" class="form-label">{{ __('branch') }}  </label>
                                    <select class="form-control form--control select2" name="branch_id" id="branch_id"  >
                                        <option  value="">{{ __('select') }}  {{ __('branch') }}</option>
                                        @foreach ($branches as $branch )
                                            <option value="{{ $branch->id }}"  >{{ $branch->name }}</option>
                                        @endforeach
                                    </select> 
                                </div> 
                            @endif
                            <div class="col-lg-3 mt-3">
                                <label for="customer_id" class="form-label">{{ __('customer') }}  </label>
                                <select class="form-control form--control select2" name="customer_id" id="customer_id"  >
                                    <option  value="">{{ __('select') }}  {{ __('customer') }}</option>
                                    <option  value="walk_customer">{{ __('walk_customer') }}</option>
                                    @foreach ($customers as $customer )
                                        <option value="{{ $customer->id }}"  >{{ $customer->name }}</option>
                                    @endforeach
                                </select> 
                            </div> 
                        </div>   
                    </div>  
                </div> 
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-3  mt-3">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-center my-3" > {{ __('total_purchase') }}</h5> 
                                <h5 class="overflow-hidden text-center my-3" > <span class="total_purchase">0</span></h5>  
                            </div>
                        </div>
                        <div class="col-lg-3  mt-3">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-center my-3" > {{ __('total_purchase_price') }}</h5> 
                                <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_sale_price">0</span></h5>  
                            </div>
                        </div>
                        <div class="col-lg-3  mt-3">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-center my-3" > {{ __('total_payments') }}</h5> 
                                <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_sale_payments">0</span></h5>  
                            </div>
                        </div>
                        <div class="col-lg-3  mt-3">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-center my-3" > {{ __('total_due') }}</h5> 
                                <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_sale_due">0</span></h5>    
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" 
                            onclick="printDiv('printsection',
                            '{{static_asset('backend/assets')}}/css/main.css',
                            '{{static_asset('backend')}}/css/reports/stock_report_print.css',
                            '{{static_asset('backend')}}/css/custom.css')"
                        >{{ __('print') }}</button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="dashboard--widget  " id="printsection">  
                        <div class="row mb-3">  
                            <h5 class="overflow-hidden text-center my-3" > {{ @Auth::user()->business->business_name }}</h5> 
                            <p class="overflow-hidden text-center my-2" > <b>{{__('customer_pos_report') }}</b></p> 
                        </div>
                        <div id="customer_pos_report_content" data-url="{{ route('reports.customer.pos.report.get.report') }}" > </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div> 
@endsection
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{static_asset('backend')}}/css/datepicker.css"/> 
@endpush
@push('scripts') 
    <script src="{{static_asset('backend')}}/js/daterangepicker.min.js"></script> 
    <script src="{{static_asset('backend')}}/js/reports/daterangepicker2.js"></script>  
    <script src="{{static_asset('backend')}}/js/pos/customer_report/customer_report.js"></script>  
    <script src="{{static_asset('backend')}}/js/pos/customer_report/customer_report_print.js"></script>  
@endpush
