@extends('backend.partials.master')
@section('title')
    {{ __('stock_transfer') }} {{ __('create') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('stock_transfer') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('stock.transfer.index') }}">{{ __('stock_transfer') }}</a> </li>
            <li>  {{ __('create') }} </li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden"> 
                            <a href="{{ route('stock.transfer.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('stock.transfer.store') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                            <div class="row mt-3">  
                                @if(business())
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('from_branch') }} <span class="text-danger">*</span> </label>
                                   <select class="form-control form--control select2" name="branch_id" id="branch_id" >
                                        <option selected disabled>{{ __('select') }} {{ __('branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @if(old('branch_id') == $branch->id) selected @endif>{{ @$branch->name }}</option>
                                        @endforeach
                                   </select>
                                    @error('branch_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                                @endif


                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('to_branch') }} <span class="text-danger">*</span></label>
                                   <select class="form-control form--control select2" name="to_branch"  >
                                        <option selected disabled>{{ __('select') }} {{ __('branch') }}</option>
                                        @foreach ($branches as $toBranch)
                                            @if(isUser() && Auth::user()->branch_id != $toBranch->id) 
                                                <option value="{{ $toBranch->id }}" @if(old('to_branch') == $toBranch->id) selected @endif>{{ @$toBranch->name }}</option>
                                            @else
                                                <option value="{{ $toBranch->id }}" @if(old('to_branch') == $toBranch->id) selected @endif>{{ @$toBranch->name }}</option>
                                            @endif
                                        @endforeach
                                   </select>
                                    @error('to_branch')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 

                                <div class="col-lg-6 mt-3">
                                    <div class="ui-widget">
                                        <label for="name" class="form-label">{{ __('product') }}  </label>
                                        <input type="text" class="form-control form--control" id="product_find" placeholder="{{ __('enter_product_info') }}" data-url="{{ route('stock.transfer.variation.location.find') }}"/> 
                                    </div>
                                    @error('variation_locations')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
 
 
                                <div class="col-lg-12">
                                    <div class="table-responsive   category-table product-view-table mt-2">
                                        <table class="table table-striped table-hover text-left">
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th>{{ __('product_name') }}</th>
                                                    <th>{{ __('quantity') }}</th>
                                                    <th>{{ __('unit_price') }}</th> 
                                                    <th>{{ __('total_unit_price') }}</th>  
                                                    <th>{{ __('action') }} </th> 
                                                </tr>
                                            </thead>
                                            <tbody id="stock_transfer_item_body">
                                                @if (old('variation_locations'))
                                                    @foreach (old('variation_locations') as $item)
                                                        @include('stocktransfer::variation_location_item_old',['item' => $item])
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle ">
                                                    <th colspan="2" class="text-left">{{ __('total_item') }}</th>  
                                                    <th colspan="5" class="text-left">: <span id="total_item" class="text-white">0</span></th>   
                                                </tr>
                                                <tr class="border-bottom bg-primary text-white head align-middle ">
                                                    <th colspan="2" class="text-left">{{ __('total_amount') }}</th>  
                                                    <th colspan="5" class="text-left">: {{ businessCurrency(business_id()) }} <span id="total_purchase_costs" class="text-white">0</span></th>   
                                                </tr>
                                            </thead>
                                        </table>
                                    </div> 
                                </div> 
                                <div class="col-lg-6 mt-3  ">
                                    <label for="applicable_tax" class="form-label">{{ __('shipping_charge') }} <span class="text-danger">*</span></label>
                                     <input type="text" name="shipping_charge" id="shipping_charge" class="form-control form--control" value="{{ old('shipping_charge',0) }}"/>
                                    @error('shpping_charge')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>    
                                <div class="col-lg-6">
                                    <div class="table-responsive   category-table product-view-table mt-3">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th class="text-left" width="50%">{{ __('shipping_charge') }} (+)</th>
                                                    <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchaseTax"> 0</span></th> 
                                                </tr>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th class="text-left" width="50%">{{ __('total_amount') }}</th>
                                                    <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchasePriceWithTax"> 0</span></th> 
                                                </tr>
                                                <input type="hidden" name="total_amount" id="total_amount" value=""/>
                                            </thead> 
                                        </table>
                                    </div> 
                                </div>
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="variation_location_url" data-url="{{ route('stock.transfer.variation.location.item') }}"/> 
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/stock_transfer/create.js"></script> 
@endpush