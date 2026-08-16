@extends('pos::master')
@section('title',__('pos_edit')) 
@section('maincontent')  
    <div class="m-5">
        <div class="row g-4">
             <div class="col-lg-7 mt-2">
                <div class="card">
                    <div class="card-body" >  
            
                        <form action="{{ route('pos.update',['id'=>$editPos->id]) }}" method="post"   >
                           @csrf
                           @method('put')
                            <div class="row">
                                <div class="col-12 text-right">
                                    <a class="btn btn-primary" href="{{ route('pos.list') }}">{{ __('list') }}</a>
                                </div>
                                @if(business())
                                    <div class="col-lg-6 mt-3">
                                        <label for="name" class="form-label">{{ __('branch') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="branch_id" id="branch_id" >
                                            <option selected value="">{{ __('select') }} {{ __('branch') }}</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" @if(old('branch_id',$editPos->branch_id) == $branch->id) selected @endif>{{ @$branch->name }}</option>
                                            @endforeach
                                    </select>
                                        @error('branch_id')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>  
                                @endif
    
                                <div class="col-lg-6 mt-3 customer-group customer  ">
                                    <label for="customer" class="form-label">{{ __('customer') }} <span class="text-danger">*</span></label>
                                   <div class="input-group">
                                       <select class="form-control form--control select2" name="customer_id" id="customer" data-url="{{ route('customers.get.customer') }}" data-customerphone="{{ __('customer_phone') }}" data-customeraddress="{{ __('customer_address') }}" >
                                           <option selected value="walk_customer">{{__('walk_customer')}}</option> 
                                           @foreach ($customers as $customer)
                                               <option value="{{ @$customer->id }}" @if($editPos->customer_id == $customer->id) selected @endif>{{ @$customer->name }} | {{ __('balance') }}: {{ $customer->balance }}</option>
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
 
                                <div class="col-lg-6 mt-3 walk_customer_phone @if($editPos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER) d-none @endif">
                                    <label for="customer" class="form-label">{{ __('customer_phone') }}</label>
                                    <div class="input-group"> 
                                        <input type="text" class="form-control form--control" name="customer_phone" value="{{ @$editPos->customer_phone }}" />
                                    </div> 
                                    @error('customer_phone')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
 
                                <div class="col-lg-6 mt-3 exist_customer exists_customer_phone">
                                    @if($editPos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
                                        <label for="customer" class="form-label">{{ __('customer_phone') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form--control" value="{{ @$editPos->customer->name }}" disabled /> 
                                        </div>
                                    @endif
                                </div> 
                                <div class="col-lg-6 mt-3 exist_customer exists_customer_address">
                                    @if($editPos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
                                    <label for="customer_address" class="form-label">{{ __('customer_address') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form--control" value="{{ @$editPos->customer->address }}" disabled /> 
                                        </div>
                                    @endif 
                                </div>  
                                <div class="col-lg-12 mt-3">
                                    <div class="ui-widget">
                                        <label for="name" class="form-label">{{ __('product') }}  </label>
                                        <input type="text" class="form-control form--control" id="product_find" placeholder="{{ __('enter_product_info') }}" data-url="{{ route('pos.variation.location.find') }}"/> 
                                    </div>
                                    @error('variation_locations')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                            </div>
                             <div class="table-responsive   category-table product-view-table mt-2">
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
                                     <tbody id="pos_item_content"> 
                                        @foreach ($editPos->posItems as $item)
                                            @include('pos::pos_edit.variation_location_item_edit',['item'=>$item])
                                        @endforeach
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
                             <div class="row">
                                 <div class="col-lg-6 mt-3">
                                     <label   class="form-label">{{ __('shipping_details') }} </label>
                                     <textarea class=" form--control" name="shipping_details" >{{ @$editPos->shipping_details }}</textarea>
                                 </div>
                                 <div class="col-lg-6 mt-3">
                                     <label  class="form-label">{{ __('shipping_address') }} </label>
                                     <textarea class="form--control " name="shipping_address">{{ @$editPos->shipping_address }}</textarea>
                                 </div>
                                 <div class="col-lg-6 mt-3">
                                     <label  class="form-label">{{ __('shipping_charge') }} </label>
                                      <input type="text" name="shipping_charge" id="shipping_charge" class="form-control form--control" value="{{ @$editPos->shipping_charge }}"/>
                                 </div>
                                 <div class="col-lg-6 mt-3">
                                     <label  class="form-label">{{ __('shipping_status') }} </label>
                                     <select class="form-control form--control select2" name="shipping_status">
                                         <option disabled selected>{{ __('select') }} {{ __('shipping_status') }}</option>
                                         @foreach (\Config::get('pos_default.shpping_status') as $key=>$shippingStatus)
                                             <option value="{{ $key }}" @if($editPos->shipping_status == $key) selected @endif>{{ __($shippingStatus) }}</option>
                                         @endforeach
                                     </select>
                                 </div> 
                                 <div class="col-lg-6 mt-3">
                                    <label for="applicable_tax" class="form-label">{{ __('order_tax') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="tax_id" id="applicableTax" data-url="{{ route('pos.taxrate.get') }}" > 
                                        <option disabled selected> {{ __('select') }} {{ __('tax') }}</option> 
                                        @foreach ($taxRates as $tax)
                                            <option value="{{ $tax->id }}" @if(old('tax_id',$editPos->order_tax_id) == $tax->id) selected @endif>{{ @$tax->name }}</option>
                                        @endforeach 
                                    </select>
                                    @error('tax_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror 
                                </div>
                                 <div class="col-lg-6 mt-3"> 
                                     <label for="discount_amount" class="form-label">{{ __('discount_amount') }} </label>
                                         <input type="text" name="discount_amount" id="discount_amount" class="form-control form--control" value="{{ $editPos->discount_amount }}"/> 
                                 </div>
                             </div>
                             <div class="table-responsive   category-table product-view-table mt-3">
                                 <table class="table table-striped table-hover">
                                     <thead>
                                         <tr class="border-bottom bg-primary text-white head align-middle">
                                             <th class="text-left" width="50%">{{ __('shipping_charge') }} (+)</th>
                                             <th class="text-left">: {{ businessCurrency(business_id()) }}<span class="text-white" id="total_shipping_charge">{{ $editPos->shipping_charge?? 0 }}</span></th> 
                                         </tr>
     
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

                                         <input type="hidden" name="total_price" id="total_price" value="{{ $editPos->total_price }}"/>
                                         <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="{{ $editPos->total_tax_amount }}"/>
                                         <input type="hidden" name="total_sell_price" id="total_sell_price" value="{{ $editPos->total_sell_price }}"/>
                                         

                                     </thead> 
                                 </table>
                             </div>  
                             <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-primary text-capitalize">{{ __('total_payable') }}: {{ businessCurrency(business_id()) }}<span id="totlpayable">0</span></button>
                                    <button type="submit" id="cash" class="btn btn-success m-2">{{ __('cash') }}</button>
                                </div>
                             </div>
                         </form> 
                    </div>
                </div>  
            </div>
            <div class="col-lg-5 pos-items mt-0"> 
                <div class="row " >
                    <div class="col-lg-6">
                        <select class="form-control form--control bg-white" id="category_id" >
                            <option selected disabled>{{ __('select') }} {{ __('category') }}</option>
                            <option value=""> {{ __('all') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class="col-lg-6">
                        <select class="form-control form--control bg-white" id="brand_id" >
                            <option selected disabled>{{ __('select') }} {{ __('brand') }}</option>
                            <option value=""> {{ __('all') }}</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div> 
                </div> 
                <div class="row pos-item-content" data-url="{{ route('pos.products') }}">
                    {{-- items --}}
                </div> 
                <div class="row pos-loader-contnet"> 
                    <div class="col-lg-12 pos-load-col mt-5"> 
                        <div class="pos-loader load-circle  ">
                            <div class="loader-row">
                                <div class="text-center" >
                                    <div class="loader table-loader"></div>
                                </div>
                            </div>
                        </div> 
                        <div class="notfound text-center text-white">No Product Found.</div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <input type="hidden" id="filter_product_fetch_url" data-url="{{ route('pos.filter.products') }}"/>
    <input type="hidden" id="suggetion_page" value="0"/>  
    <input type="hidden" id="variation_location_url" data-url="{{ route('pos.variation.location.item.get') }}"/> 

@endsection 
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/pos/pos.js"></script>
    <script src="{{ static_asset('backend') }}/js/jquery.lazyload.js"></script>
    <script src="{{ static_asset('backend') }}/js/custom.lazyload.js"></script>
    @include('pos::pos_edit.edit_js')
@endpush