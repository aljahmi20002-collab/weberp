@extends('backend.partials.master')
@section('title')
    {{ __('proposal') }} {{ __('edit') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('proposal') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('sell') }}</a> </li>
            <li> <a href="#">{{ __('proposal') }}</a> </li>
            <li> {{ __('edit') }}</li>
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
                            <a href="{{ route('saleproposal.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-list"></i> {{ __('list') }}
                            </a>
                        </h4>
                        <form action="{{ route('saleproposal.update',['id'=>$sale->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf 
                            @method('put')
                            <div class="row mt-3">   
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('customer_type') }} <span class="text-danger">*</span></label>
                                   <select class="form-control form--control select2" name="customer_type" id="customer_type" >
                                         @foreach (\Config::get('pos_default.customer_type') as $key=>$type )
                                            <option value="{{ $key }}" @if(old('customer_type',$sale->customer_type) == $key) selected @endif>{{ __($type) }}</option>
                                         @endforeach
                                   </select>
                                    @error('customer_type')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-6 mt-3 customer-group customer @if(old('customer_type',$sale->customer_type) == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) @else d-none @endif">
                                    <label for="customer" class="form-label">{{ __('customer') }} <span class="text-danger">*</span></label>
                                   <div class="input-group">
                                       <select class="form-control form--control select2" name="customer_id" id="customer" data-url="{{ route('customers.get.customer') }}" data-customerphone="{{ __('customer_phone') }}" data-customeraddress="{{ __('customer_address') }}" >
                                           <option selected disabled>{{ __('select') }} {{ __('customer') }}</option> 
                                           @foreach ($customers as $customer)
                                               <option value="{{ @$customer->id }}" @if($sale->customer_id == $customer->id) selected @endif>{{ @$customer->name }} | {{ __('balance') }}: {{ $customer->balance }}</option>
                                           @endforeach
                                      </select>
                                      <div class="input-group-prepend">
                                           <div class="input-group-text">
                                               <a class="modalBtn" href="#" data-bs-toggle="modal" data-modalsize="modal-lg" data-bs-target="#dynamic-modal" data-title="{{ __('customer') }} {{ __('create') }}" data-url="{{ route('customers.create.modal') }}"><i class="fa fa-plus"></i></a>
                                           </div>
                                       </div> 
                                   </div> 
                                    @error('customer_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 

                                <div class="col-lg-6 mt-3 walk_customer_phone @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) d-none @endif ">
                                    <label for="customer" class="form-label">{{ __('customer_phone') }}</label>
                                   <div class="input-group"> 
                                       <input type="text" class="form-control form--control" name="customer_phone" value="{{ @$sale->customer_phone }}" />
                                   </div> 
                                    @error('customer_phone')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
 
                          
                                <div class="col-lg-6 mt-3 exist_customer exists_customer_phone">
                                    @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
                                        <label for="customer" class="form-label">{{ __('customer_phone') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form--control" value="{{ @$sale->customer->name }}" disabled /> 
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-6 mt-3 exist_customer exists_customer_address">
                                    @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
                                    <label for="customer_address" class="form-label">{{ __('customer_address') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form--control" value="{{ @$sale->customer->address }}" disabled /> 
                                        </div>
                                    @endif 
                                </div> 
 
                                @if(business())
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('branch') }} </label>
                                   <select class="form-control form--control select2" name="branch_id" id="branch_id" >
                                        <option selected disabled>{{ __('select') }} {{ __('branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @if(old('branch_id',$sale->branch_id) == $branch->id) selected @endif>{{ @$branch->name }}</option>
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
                                        <input type="text" class="form-control form--control" id="product_find" placeholder="{{ __('enter_product_info') }}" data-url="{{ route('saleproposal.variation.location.find') }}"/> 
                                    </div>
                                    @error('variation_locations')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
                                <div class="col-lg-12">
                                    <div class="table-responsive  category-table product-view-table mt-2">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th>{{ __('product_name') }}</th>
                                                    <th>{{ __('quantity') }}</th>
                                                    <th>{{ __('unit_price') }}</th> 
                                                    <th>{{ __('total_unit_price') }}</th>  
                                                    <th>{{ __('action') }} </th> 
                                                </tr>
                                            </thead>
                                            <tbody id="sale_item_body"> 
                                                {{-- variation location item will appends --}}
                                                @foreach ($sale->saleItems as $item)
                                                    @include('saleproposal::variation_location_item_edit')
                                                @endforeach
                                            </tbody>
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle ">
                                                    <th colspan="2" class="text-left">{{ __('total_item') }}</th>  
                                                    <th colspan="5" class="text-left">: <span id="total_item" class="text-white">{{ @$sale->saleItems->sum('sale_quantity') }}</span></th>   
                                                </tr>
                                                <tr class="border-bottom bg-primary text-white head align-middle ">
                                                    <th colspan="2" class="text-left">{{ __('total_amount') }}</th>  
                                                    <th colspan="5" class="text-left">: {{ businessCurrency(business_id()) }} <span id="total_purchase_costs" class="text-white">{{ @$sale->saleItems->sum('total_unit_price') }}</span></th>   
                                                </tr>
                                            </thead>
                                        </table>
                                    </div> 
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label   class="form-label">{{ __('shipping_details') }} </label>
                                    <textarea class="form-control form--control" name="shipping_details" >{{ @$sale->shipping_details }}</textarea>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label  class="form-label">{{ __('shipping_address') }} </label>
                                    <textarea class="form-control form--control " name="shipping_address">{{ @$sale->shipping_address }}</textarea>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label  class="form-label">{{ __('shipping_charge') }} </label>
                                     <input type="text" name="shipping_charge" id="shipping_charge" class="form-control form--control" value="{{ @$sale->shipping_charge }}"/>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label  class="form-label">{{ __('shipping_status') }} </label>
                                    <select class="form-control form--control select2" name="shipping_status">
                                        <option disabled selected>{{ __('select') }} {{ __('shipping_status') }}</option>
                                        @foreach (\Config::get('pos_default.shpping_status') as $key=>$shippingStatus)
                                            <option value="{{ $key }}" @if($sale->shipping_status == $key) selected @endif>{{ __($shippingStatus) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-12" >
                                    <div class="row">
                                        <div class="col-lg-6 mt-3">
                                            <div>
                                                <label for="applicable_tax" class="form-label">{{ __('order_tax') }} <span class="text-danger">*</span></label>
                                                <select class="form-control form--control select2" name="tax_id" id="applicableTax" data-url="{{ route('saleproposal.taxrate.get') }}" > 
                                                    <option disabled selected> {{ __('select') }} {{ __('tax') }}</option> 
                                                    @foreach ($taxRates as $tax)
                                                        <option value="{{ $tax->id }}" @if(old('tax_id',$sale->order_tax_id) == $tax->id) selected @endif>{{ @$tax->name }}</option>
                                                    @endforeach 
                                                </select>
                                                @error('tax_id')
                                                    <p class="text-danger pt-2">{{ $message }}</p>
                                                @enderror 
                                            </div>
                                            <div>
                                                <label for="applicable_tax" class="form-label">{{ __('discount_amount') }} </label>
                                                 <input type="text" name="discount_amount" id="discount_amount" class="form-control form--control" value="{{ $sale->discount_amount }}"/>
                                            </div>
                                        </div> 
                                        <div class="col-lg-6 mt-4">
                                            <div class="table-responsive  category-table product-view-table mt-3">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('shipping_charge') }} (+)</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="total_shipping_charge"> {{ $sale->shipping_charge }}</span></th> 
                                                        </tr>

                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('order_tax') }} (+)</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchaseTax"> {{ number_format(($sale->saleItems->sum('total_unit_price')/100) * $sale->TaxRate->tax_rate) }}</span></th> 
                                                        </tr>
                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('discount_amount') }} (-)</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="total_discount_amount"> {{ $sale->discount_amount }}</span></th> 
                                                        </tr>
                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('total_amount_include_tax') }}</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchasePriceWithTax">
                                                                    {{ $sale->total_sale_price }}
                                                                </span></th> 
                                                        </tr>
                                                    </thead>
                                                   
                                                </table>
                                            </div> 
                                        </div> 
                                    </div>
                                </div> 
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="variation_location_url" data-url="{{ route('saleproposal.variation.location.item') }}"/> 
    <input type="hidden" id="existing_customer" value="{{ \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER }}" />
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/saleproposal/create.js"></script> 
@endpush