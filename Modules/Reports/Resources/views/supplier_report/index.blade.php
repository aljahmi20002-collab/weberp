@extends('backend.partials.master')
@section('title',__('supplier_report'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('reports') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('reports') }}</a> </li> 
            <li class="active">  {{ __('supplier_report') }} </li>
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
                            <div class="col-lg-3 mt-3">
                                <label for="supplier_id" class="form-label">{{ __('supplier') }}  </label>
                                <select class="form-control form--control select2" name="supplier_id" id="supplier_id">
                                    <option  value="">{{ __('select') }}  {{ __('supplier') }}</option>
                                    @foreach ($suppliers as $supplier )
                                        <option value="{{ $supplier->id }}" >{{ $supplier->name }}</option>
                                    @endforeach
                                </select> 
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
                            </div>
                            @endif  
                            <div class="col-lg-3 mt-3">
                                <label for="report_type" class="form-label">{{ __('report_type') }}  </label>
                                <select class="form-control form--control select2" name="report_type" id="report_type">
                                    <option  value="">{{ __('select') }}  {{ __('branch') }}</option> 
                                    <option value="">{{ __('purchase_report') }}</option>
                                    <option value="purchase_return_report"  >{{ __('purchase_return_report') }}</option>
                                </select> 
                            </div>
                        </div>   
                    </div>  
                </div> 
                <div class="row mt-3">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" 
                            onclick="printDiv('printsection',
                            '{{static_asset('backend/assets')}}/css/main.css',
                            '{{static_asset('backend')}}/css/reports/profitloss_print.css',
                            '{{static_asset('backend')}}/css/custom.css')"
                        >{{ __('print') }}</button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="dashboard--widget  " id="printsection">  
                        <div class="row mb-3">  
                            <h5 class="overflow-hidden text-center my-3" > {{ @Auth::user()->business->business_name }}</h5>  
                        </div>
                        <div id="supplier_report_content" data-url="{{ route('reports.supplier.report.get.report') }}" > </div>
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
    <script src="{{static_asset('backend')}}/js/daterangepicker/daterangepicker.js"></script> 
    <script src="{{static_asset('backend')}}/js/reports/daterangepicker.js"></script>  
    <script src="{{static_asset('backend')}}/js/reports/supplier_report/supplier_report.js"></script> 
    <script src="{{static_asset('backend')}}/js/reports/supplier_report/supplier_report_print.js"></script> 
@endpush
 