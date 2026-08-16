@extends('backend.partials.master')
@section('title')
    {{ __('service_sale') }} {{ __('create') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('sell') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('sell') }}</a> </li> 
            <li>  {{ __('service_sale') }} </li>
            <li class="active">  {{ __('create') }} </li>
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
                            <a href="{{ route('servicesale.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-list"></i> {{ __('list') }}
                            </a>
                        </h4>
                        <form action="{{ route('servicesale.store') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                            <div class="row mt-3">   
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('customer_type') }} <span class="text-danger">*</span></label>
                                
                                       <select class="form-control form--control select2" name="customer_type" id="customer_type" >
                                            @foreach (\Config::get('pos_default.customer_type') as $key=>$type )
                                               <option value="{{ $key }}" @if(old('customer_type',\Modules\Customer\Enums\CustomerType::WALK_CUSTOMER) == $key) selected @endif>{{ __($type) }}</option>
                                            @endforeach
                                      </select>  
                                    @error('customer_type')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-6 mt-3 customer-group customer @if(old('customer_type') == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) @else d-none @endif">
                                    <label for="customer" class="form-label">{{ __('customer') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control form--control select2" name="customer_id" id="customer" data-url="{{ route('customers.get.customer') }}" data-customerphone="{{ __('customer_phone') }}" data-customeraddress="{{ __('customer_address') }}" >
                                                <option selected disabled>{{ __('select') }} {{ __('customer') }}</option> 
                                                @foreach ($customers as $customer)
                                                    <option value="{{ @$customer->id }}" @if(old('customer_id') == $customer->id) selected @endif>{{ @$customer->name }} | {{ __('balance') }}: {{ $customer->balance }}</option>
                                                @endforeach
                                        </select>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a class="modalBtn" href="#" data-bs-toggle="modal" data-modalsize="modal-lg" data-bs-target="#dynamic-modal" data-title="{{ __('customer') }} {{ __('create') }}" 
                                                data-url="{{ route('customers.create.modal') }}"
                                                ><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div> 
                                    </div> 
                                    @error('customer_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
 
                                <div class="col-lg-6 mt-3 walk_customer_phone ">
                                    <label for="customer" class="form-label">{{ __('customer_phone') }}</label>
                                   <div class="input-group"> 
                                       <input type="text" class="form-control form--control" name="customer_phone" />
                                   </div> 
                                    @error('customer_phone')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
 
                                <div class="col-lg-6 mt-3 exist_customer exists_customer_phone"> </div> 
                                <div class="col-lg-6 mt-3 exist_customer exists_customer_address"> </div>  
  
                                @if(business())
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('branch') }} <span class="text-danger">*</span></label>
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
                                        <label for="name" class="form-label">{{ __('service') }}  </label>
                                        <input type="text" class="form-control form--control" id="product_find" placeholder="{{ __('enter_service_info') }}" data-url="{{ route('servicesale.service.find') }}"/> 
                                    </div>
                                    @error('service_items')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
  
                                <div class="col-lg-12">
                                    <div class="table-responsive   category-table product-view-table mt-2">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="border-bottom bg-primary text-white head align-middle">
                                                    <th>{{ __('service_name') }}</th>
                                                    <th>{{ __('quantity') }}</th>
                                                    <th>{{ __('unit_price') }}</th> 
                                                    <th>{{ __('total_unit_price') }}</th>  
                                                    <th>{{ __('action') }} </th> 
                                                </tr>
                                            </thead>
                                            <tbody id="sale_item_body">  
                                                @if (old('service_items')) 
                                                    @foreach (old('service_items') as $service_item) 
                                                        @include('servicesale::service_item_old',['item'=>$service_item])
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
                                                    <th colspan="2" class="text-left">{{ __('total_amount') }}</th>  
                                                    <th colspan="5" class="text-left">: {{ businessCurrency(business_id()) }} <span id="total_purchase_costs" class="text-white">0</span></th>   
                                                </tr>
                                            </thead>
                                        </table>
                                    </div> 
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label   class="form-label">{{ __('shipping_details') }} </label>
                                    <textarea class="form-control form--control" name="shipping_details" >{{ old('shipping_details') }}</textarea>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label  class="form-label">{{ __('shipping_address') }} </label>
                                    <textarea class="form-control form--control " name="shipping_address">{{ old('shipping_address') }}</textarea>
                                </div>
                              

                                <div class="col-lg-12" >
                                    <div class="row">
                                        <div class="col-lg-6 mt-3">
                                            <div>
                                                <label for="applicable_tax" class="form-label">{{ __('order_tax') }} <span class="text-danger">*</span></label>
                                                <select class="form-control form--control select2" name="tax_id" id="applicableTax" data-url="{{ route('servicesale.taxrate.get') }}" > 
                                                    <option disabled selected> {{ __('select') }} {{ __('tax') }}</option> 
                                                    @foreach ($taxRates as $tax)
                                                        <option value="{{ $tax->id }}" @if(old('tax_id') == $tax->id) selected @endif>{{ @$tax->name }}</option>
                                                    @endforeach 
                                                </select>
                                                @error('tax_id')
                                                    <p class="text-danger pt-2">{{ $message }}</p>
                                                @enderror 
                                            </div>
                                            <div>
                                                <label for="applicable_tax" class="form-label">{{ __('discount_amount') }} </label>
                                                 <input type="text" name="discount_amount" id="discount_amount" class="form-control form--control" value="{{ old('discount_amount') }}"/>
                                            </div>
                                        </div> 
                                        <div class="col-lg-6 mt-4">
                                            <div class="table-responsive category-table product-view-table mt-3">
                                                <table class="table table-striped table-hover">
                                                    <thead> 
                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('order_tax') }} (+)</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchaseTax"> 0</span></th> 
                                                        </tr>
                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('discount_amount') }} (-)</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="total_discount_amount"> 0</span></th> 
                                                        </tr>
                                                        <tr class="border-bottom bg-primary text-white head align-middle">
                                                            <th class="text-left" width="50%">{{ __('total_amount_include_tax') }}</th>
                                                            <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="totlPurchasePriceWithTax"> 0</span></th> 
                                                        </tr>

                                                                  
                                                        <input type="hidden" name="total_price" id="total_price" value=""/>
                                                        <input type="hidden" name="total_tax_amount" id="total_tax_amount" value=""/>
                                                        <input type="hidden" name="total_sell_price" id="total_sell_price" value=""/>

                                                    </thead>
                                                   
                                                </table>
                                            </div> 
                                        </div> 
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
    
    <input type="hidden" id="variation_location_url" data-url="{{ route('servicesale.service.item') }}"/> 
    <input type="hidden" id="existing_customer" value="{{ \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER }}" />
@endsection
 
@push('scripts')  
    <script type="text/javascript" src="{{ static_asset('backend/js/servicesale/create.js') }}"></script>
@endpush