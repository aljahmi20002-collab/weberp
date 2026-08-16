@extends('backend.partials.master')
@section('title')
    {{ __('purchase') }} {{ __('return') }} {{ __('create') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('purchase_return') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('purchase.index') }}">{{ __('purchase') }}</a> </li>
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
                            <a href="{{ route('purchase.return.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('purchase.return.store') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                            <div class="row mt-3">  
                                <div class="col-lg-6 customer-group mt-3">
                                    <label for="name" class="form-label">{{ __('supplier') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control form--control select2" name="supplier_id" >
                                            <option selected disabled>{{ __('select') }} {{ __('supplier') }}</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" @if(old('supplier_id') == $supplier->id) selected @endif>{{ @$supplier->name }} ({{ @$supplier->company_name }})</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a class="modalBtn" href="#" data-bs-toggle="modal" data-modalsize="modal-lg" data-bs-target="#dynamic-modal" data-title="{{ __('supplier') }} {{ __('create') }}" 
                                                data-url="{{ route('suppliers.create.modal') }}"
                                                ><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div> 
                                    </div>
 
                                    @error('supplier_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                @if(business())
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('branch') }} </label>
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
                                    <div class="ui-widget">
                                        <label for="name" class="form-label">{{ __('product') }}  </label>
                                        <input type="text" class="form-control form--control" id="product_find" placeholder="{{ __('enter_product_info') }}" data-url="{{ route('purchase.return.variation.location.find') }}"/> 
                                    </div>
                                    @error('variation_locations')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
 
                                <div class="col-lg-12">
                                    <div class="table-responsive   category-table product-view-table mt-2">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th>{{ __('product_name') }}</th>
                                                    <th>{{ __('return_quantity') }}</th>
                                                    <th>{{ __('unit_price') }}</th> 
                                                    <th>{{ __('total_unit_price') }}</th>  
                                                    <th>{{ __('action') }} </th> 
                                                </tr>
                                            </thead>
                                            <tbody id="purchase_item_body"> 
                                                @if (old('variation_locations'))
                                                    @foreach (old('variation_locations') as $item )
                                                        @include('purchase::purchase-return.variation_location_item_old',['return_item'=>$item])
                                                    @endforeach
                                                @endif
                                                {{-- variation location item will appends --}}
                                            </tbody>
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle ">
                                                    <th colspan="2" class="text-left">{{ __('total_item') }}</th>  
                                                    <th colspan="5" class="text-left">: <span id="total_item" class="text-white">0</span></th>   
                                                </tr>
                                                <tr class="border-bottom bg-primary text-white head align-middle ">
                                                    <th colspan="2" class="text-left">{{ __('total_purchase_return_amount') }}</th>  
                                                    <th colspan="5" class="text-left">: {{ businessCurrency(business_id()) }} <span id="total_purchase_costs" class="text-white">0</span></th>   
                                                </tr>
                                            </thead>
                                        </table>
                                    </div> 
                                </div>

                                <div class="col-lg-6 mt-3  ">
                                    <label for="applicable_tax" class="form-label">{{ __('purchase_tax') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="tax_id" id="applicableTax" data-url="{{ route('purchase.taxrate.get') }}" > 
                                        <option disabled selected> {{ __('select') }} {{ __('tax') }}</option> 
                                        @foreach ($taxRates as $tax)
                                            <option value="{{ $tax->id }}" @if(old('tax_id') == $tax->id) selected @endif>{{ @$tax->name }}</option>
                                        @endforeach 
                                    </select>
                                    @error('tax_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>    
                                <div class="col-lg-6">
                                    <div class="table-responsive   category-table product-view-table mt-3">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th class="text-left" width="50%">{{ __('purchase_tax') }} (+)</th>
                                                    <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchaseTax"> 0</span></th> 
                                                </tr>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th class="text-left" width="50%">{{ __('total_purchase_return_amount_include_tax') }}</th>
                                                    <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchasePriceWithTax"> 0</span></th> 
                                                </tr>

                                                <input type="hidden" name="total_price" id="total_price" value=""/>
                                                <input type="hidden" name="total_tax_amount" id="total_tax_amount" value=""/>
                                                <input type="hidden" name="total_buy_cost" id="total_buy_cost" value=""/>
                                                
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
    <input type="hidden" id="variation_location_url" data-url="{{ route('purchase.return.variation.location.item') }}"/> 
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/purchase/purchase_return/create.js"></script> 
@endpush