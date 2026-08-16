@extends('backend.partials.master')
@section('title',__('profit_loss_reports'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('reports') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('reports') }}</a> </li> 
            <li class="active">  {{ __('profit_loss') }} </li>
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
                                @error('date')
                                    <p class="text-danger py-2">{{ $message }}</p>
                                @enderror
                            </div>
                            @if(business() || isSuperadmin())
                            <div class="col-lg-3 mt-3">
                                <label for="branch" class="form-label">{{ __('branch') }}  </label>
                                <select class="form-control form--control select2" name="branch_id" id="branch_id">
                                    <option  value="">{{ __('select') }}  {{ __('branch') }}</option>
                                    @foreach ($branches as $branch )
                                        <option value="{{ $branch->id }}" @if(old('branch_id',$request->branch_id) == $branch->id) selected @endif>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                <p class="text-danger py-2">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif 
                        </div> 
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="button" 
                            onclick="printDiv('printsection',
                            '{{static_asset('backend/assets')}}/css/main.css',
                            '{{static_asset('backend')}}/css/reports/profitloss_print.css',
                            '{{static_asset('backend')}}/css/custom.css')"
                             class="btn btn-primary">{{ __('print') }}</button>
                        </div>
                    </div>
                <div id="printsection"> 
                    <div class="filter_row row"> 
                        <div class="col-lg-12 px-3 " style="padding-left:10px"> 
                            <h5 class="overflow-hidden text-center my-3" > {{ @Auth::user()->business->business_name }}</h5> 
                            <p class="overflow-hidden text-center my-2" > <b>{{__('profit_loss_report') }}</b></p> 
                            <div class="d-flex mt-3 align-items-center justify-content-between">
                                <div class="w-50">{{ __('date') }}:</div>
                                <div class="filter_date_div"></div>
                            </div> 
                            <div class="d-flex mt-3 align-items-center justify-content-between">
                                <div class="w-50">{{ __('branch') }}:</div>
                                <div class="filter_branch_div"></div>
                            </div>  
                        </div>
                    </div>
                    <hr class="print_break d-none"/>
                    <div class="row">
                        <div class="col-lg-3  mt-3">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-left my-3" > {{ __('total_sales') }}</h5> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_selling_price') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_sales_price">0</span></div>
                                </div>
                                  
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_sales_tax') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_sale_tax_amount">0</span></div>
                                </div> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_sales_shipping_charge') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_sale_shipping_charge">0</span></div>
                                </div> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_sales_discount') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_sale_discount_amount">0</span></div>
                                </div>  
                            </div>
                        </div>
                          
                        <div class="col-lg-3   mt-3 ">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-left my-3" > {{ __('total_pos_sales') }}</h5> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_pos_selling_price') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_pos_sale_price">0</span></div>
                                </div>
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_pos_sales_tax') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_pos_sale_tax_amount">0</span></div>
                                </div> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_pos_sales_shipping_charge') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_pos_sale_shipping_charge">0</span></div>
                                </div> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_pos_sales_discount') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_pos_sale_discount_amount">0</span></div>
                                </div>  
                            </div>
                        </div>
                        <div class="col-lg-3  mt-3"> 
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-left my-3" > {{ __('total_purchase') }}</h5> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_purchase_price') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_purchase_cost">0</span></div>
                                </div>
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_purchase_tax') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_purchase_tax_amount">0</span></div>
                                </div> 
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_purchase_return_price') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_purchase_return_price">0</span></div>
                                </div>
                                <div class="d-flex mt-3 justify-content-between align-items-center">
                                    <div class="w-50">{{ __('total_purchase_return_tax') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_purchase_return_tax_amount">0</span></div>
                                </div>
                            </div>
                        </div>
                         
                        <div class="col-lg-3  mt-3">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-left my-3" > {{ __('total_transfer') }} & {{ __('total_income') }}</h5>  
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50"><small>{{ __('total_transfer_amount') }}</small>:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_transfer_price">0</span></div>
                                </div>
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50"><small>{{ __('total_transfer_shipping_charge') }}</small>:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_shipping_charge">0</span></div>
                                </div>
                                
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_income') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_income">0</span></div>
                                </div>
                                <div class="d-flex mt-3 align-items-center justify-content-between">
                                    <div class="w-50">{{ __('total_expense') }}:</div>
                                    <div>{{ businessCurrency(business_id())}} <span class="total_expense">0</span></div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr class="print_break d-none"/>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-end " ></h5>
                                <div>
                                    <h5>{{ __('gross_profit') }}: <font class="grossprofitfont">{{ businessCurrency(business_id()) }}<span class="total_gross_profit">0</span></font></h5>
                                    <p class="mt-2"><small>( Total sales price + Total pos sales price) - Total purchase price</small></p>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="dashboard--widget  height100">   
                                <h5 class="overflow-hidden text-end " ></h5>
                                <div>
                                    <h5> {{ __('net_profit') }}: <font class="netprofitfont">{{ businessCurrency(business_id()) }}<span class="total_net_profit">0</span></font></h5>
                                    <p class="mt-2">
                                        <small>(Gross profit + total sales tax + total sales shipping charge + total pos tax + total pos shipping charge)
                                         - 
                                         (Total sales discount + total pos discount + total purchase tax + total transfer shipping charge + total expense)</small></p>
                                </div>
                            </div> 
                        </div>  
                    </div>
                </div>

            </div>
        </div> 
    </div>
    <input type="hidden" id="profit_loss_url" data-url="{{ route('reports.profit.loss.get.profit') }}"/>
    <input type="hidden" id="purchase_profit_loss_url" data-url="{{ route('reports.profit.loss.get.purchase.profit') }}"/>
@endsection
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{static_asset('backend')}}/css/datepicker.css"/> 
@endpush
 @push('scripts')
    <script src="{{static_asset('backend')}}/js/daterangepicker.min.js"></script>  
    <script src="{{static_asset('backend')}}/js/daterangepicker/daterangepicker.js"></script>
    <script src="{{static_asset('backend')}}/js/reports/daterangepicker.js"></script>
    <script src="{{static_asset('backend')}}/js/reports/profit_loss.js"></script>
    <script src="{{static_asset('backend')}}/js/reports/profit_loss_print.js"></script>
 @endpush