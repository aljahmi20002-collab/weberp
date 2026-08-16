@extends('backend.partials.master')
@section('title',__('product_wise_profit'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('reports') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#"> {{ __('reports') }}</a> </li> 
            <li class="active">  {{ __('product_wise_sale_profit') }} </li>
        </ul>
    </div>
@endsection

@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">  
                        <form action="{{ route('reports.product.wise.profit.get') }}" method="get">
                            @method('get')
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
                                <div class="col-lg-3 mt-5"> 
                                    <button type="submit" class="btn btn-primary">{{ __('reports') }}</button>
                                </div>
                            </div> 
                        </form>
 
                    </div>  
                </div> 
                <div class="row mt-3">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" 
                            onclick="printOnlyDiv('printsection')"
                        >{{ __('print') }}</button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="dashboard--widget  " id="printsection">  
                        <div class="row mb-3">  
                            <h5 class="overflow-hidden text-center my-3" > {{ @Auth::user()->business->business_name }}</h5> 
                            <p class="overflow-hidden text-center my-2" > <b>{{__('product_wise_sale_profit') }} {{ __('reports') }}</b></p> 
                            <div class="col-lg-12 px-3 " style="padding-left:10px"> 
                                <div class="d-flex mt-3 align-items-center">
                                    <div class="width-100px" >{{ __('date') }}:</div>
                                    <div >{{ $request->date }}</div>
                                </div> 
                                @if(!isUser() && isset($branchname))
                                    <div class="d-flex mt-3 align-items-center ">
                                        <div class="width-100px" >{{ __('branch') }}:</div>
                                        <div >{{ $branchname->name }}</div>
                                    </div>  
                                @else
                                    <div class="d-flex mt-3 align-items-center ">
                                        <div class="width-100px" >{{ __('branch') }}:</div>
                                        <div >{{ @Auth::user()->branch->name }}</div>
                                    </div>  
                                @endif
                            </div>
                        </div>
                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive  ">
                            <table class="table table-striped table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>
                                        <th>{{ __('product') }}</th> 
                                        <th>{{ __('gross_profit') }}</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach ($products as $key=>$product) 
                                        <tr>
                                            <td data-title="#" class="width-5">{{ ++$i }}</td>
                                            <td data-title="{{ __('product') }}">{{ @productVariation($key)->ItemName  }}  </td>
                                            <td data-title="{{ __('gross_profit') }}"> {{ businessCurrency(business_id()) }} {{ number_format(productTotalSalePrice($product) -  productTotalPurchaseCost($product,$request->date),2)  }} </td> 
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Responsive Dashboard Table -->
                        <!-- Pagination -->
                        <div class="d-flex flex-row-reverse align-items-center pagination-content">
                        <span class="paginate pagination-number-link">{{ $products->appends($request->all())->links() }}</span>
                        <p class="p-2 small paginate">
                            {!! __('Showing') !!}
                            <span class="font-medium">{{ $products->firstItem() }}</span>
                            {!! __('to') !!}
                            <span class="font-medium">{{ $products->lastItem() }}</span>
                            {!! __('of') !!}
                            <span class="font-medium">{{ $products->total() }}</span>
                            {!! __('results') !!}
                        </p>
                        </div>
                        <!-- Pagination -->
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
    @if($request->filter)
    <script src="{{static_asset('backend')}}/js/reports/daterangepicker2.js"></script> 
    @else
    <script src="{{static_asset('backend')}}/js/reports/daterangepicker.js"></script> 
    @endif 
 @endpush