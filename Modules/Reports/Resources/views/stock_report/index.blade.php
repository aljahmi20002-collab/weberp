@extends('backend.partials.master')
@section('title',__('stock_report'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('reports') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('reports') }}</a> </li> 
            <li class="active">  {{ __('stock_report') }} </li>
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
                            <div class="col-lg-3 mt-3">
                                <label for="category_id" class="form-label">{{ __('category') }}  </label>
                                <select class="form-control form--control select2" name="category_id" id="category_id" data-url="{{ route('reports.stock.report.subcategory') }}">
                                    <option  value="">{{ __('select') }}  {{ __('category') }}</option>
                                    @foreach ($categories as $category )
                                        <option value="{{ $category->id }}"  >{{ $category->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="col-lg-3 mt-3">
                                <label for="subcategory_id" class="form-label">{{ __('subcategory') }}  </label>
                                <select class="form-control form--control select2" name="subcategory_id" id="subcategory_id"  >
                                    <option  value="">{{ __('select') }}  {{ __('category') }}</option> 
                                </select> 
                            </div>
                            <div class="col-lg-3 mt-3">
                                <label for="brand_id" class="form-label">{{ __('brand') }}  </label>
                                <select class="form-control form--control select2" name="brand_id" id="brand_id"  >
                                    <option  value="">{{ __('select') }}  {{ __('brand') }}</option> 
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="col-lg-3 mt-3">
                                <label for="unit_id" class="form-label">{{ __('unit') }}  </label>
                                <select class="form-control form--control select2" name="unit_id" id="unit_id"  >
                                    <option  value="">{{ __('select') }}  {{ __('brand') }}</option> 
                                    @foreach ($units as $unit )
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>   
                    </div>  
                </div> 
                <div class="row">
                    <div class="col-lg-3  mt-3">
                        <div class="dashboard--widget  height100">   
                            <h5 class="overflow-hidden text-center my-3" > {{ __('total_stock_selling_price') }}</h5> 
                            <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_stock_selling_price">0</span></h5>  
                        </div>
                    </div>
                    <div class="col-lg-3  mt-3">
                        <div class="dashboard--widget  height100">   
                            <h5 class="overflow-hidden text-center my-3" > {{ __('total_stock_purchase_price') }}</h5> 
                            <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_stock_purchase_price">0</span></h5>  
                        </div>
                    </div>
                    <div class="col-lg-3  mt-3">
                        <div class="dashboard--widget  height100">   
                            <h5 class="overflow-hidden text-center my-3" > {{ __('total_stock_gross_profit') }}</h5> 
                            <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_stock_gross_profit">0</span></h5>   
                            <p class="text-center"> <small> ({{ __('total_stock_selling_price') }} -  {{ __('total_stock_purchase_price') }})</small></p> 
                        </div>
                    </div>
                    <div class="col-lg-3  mt-3">
                        <div class="dashboard--widget  height100">   
                            <h5 class="overflow-hidden text-center my-3" > {{ __('total_stock_tax_amount') }}</h5> 
                            <h5 class="overflow-hidden text-center my-3" > {{ businessCurrency(business_id()) }} <span class="total_stock_tax_amount">0</span></h5>   
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
                            <p class="overflow-hidden text-center my-2" > <b>{{__('stock_report') }}</b></p> 
                            <div class="col-lg-12 px-3 " style="padding-left:10px">  
                                @if(!isUser())
                                    <div class="d-flex mt-3 align-items-center ">
                                        <div class="width-100px" >{{ __('branch') }}:</div>
                                        <div class="branch_name" >{{ __('all') }}</div>
                                    </div>  
                                @else
                                    <div class="d-flex mt-3 align-items-center ">
                                        <div class="width-100px" >{{ __('branch') }}:</div>
                                        <div >{{ @Auth::user()->branch->name }}</div>
                                    </div>  
                                @endif
                            </div> 
                        </div>
                        <div id="stock_report_content" data-url="{{ route('reports.stock.report.get.report') }}" > 
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div> 
@endsection
 
@push('scripts') 
    <script src="{{static_asset('backend')}}/js/reports/stock_report/stock_report.js"></script> 
    <script src="{{static_asset('backend')}}/js/reports/stock_report/stock_report_print.js"></script>  
@endpush
