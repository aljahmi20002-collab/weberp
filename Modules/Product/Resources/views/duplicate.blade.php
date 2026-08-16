

@extends('backend.partials.master')
@section('title')
    {{ __('product') }} {{ __('duplicate') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('products') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('product.index') }}">{{ __('product') }}</a> </li>
            <li>  {{ __('duplicate') }} </li>
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
                            <a href="{{ route('product.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('product.duplicate.store') }}" method="post" enctype="multipart/form-data">
                            @csrf  
                            <div class="row mt-3"> 
                                <div class="col-lg-6 mt-3">
                                    <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control"  id="name" value="{{ old('name',@$product->name) }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
                                <div class="col-lg-6 mt-3">
                                    <label for="product_image" class="form-label">{{ __('product_image') }}  </label>
                                    <input type="file" name="product_image" class="form-control form--control"  id="product_image" value="{{ old('product_image') }}">
                                    @error('product_image')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
       
                                <div class="col-lg-6 mt-3">
                                    <label for="barcode_type" class="form-label">{{ __('barcode_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="barcode_type">
                                        @foreach (\Config::get('pos_default.barcode_types') as $key=>$type )
                                            <option value="{{ $key }}" @if(old('barcode_type',@$product->barcode_type) == $key) selected @endif>{{ __($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('barcode_type')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-6 mt-3">
                                    <label for="unit" class="form-label">{{ __('unit') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="unit_id" id="unit_id" data-url="{{ route('product.units') }}"> 
                                        <option  selected disabled>   {{ __('select') }} {{ __('unit') }}  </option> 
                                        @if(!isSuperadmin())
                                            @foreach ($units as $unit)
                                                <option  value="{{ $unit->id }}" @if(old('unit_id',@$product->unit_id) == $unit->id) selected @endif> {{ $unit->name }}  </option> 
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('unit_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                        
                                <div class="col-lg-6 mt-3">
                                    <label for="brand" class="form-label">{{ __('brand') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="brand_id" id="brand_id" data-url="{{ route('product.brands') }}">
                                        <option  selected disabled>   {{ __('select') }} {{ __('brand') }}  </option> 
                                        @if(!isSuperadmin())
                                            @foreach ($brands as $brand )
                                                <option value="{{ $brand->id }}" @if(old('brand_id',@$product->brand_id) == $brand->id) selected @endif>{{ @$brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('brand_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
                                <div class="col-lg-6 mt-3">
                                    <label for="warranty" class="form-label">{{ __('warranty') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="warranty_id" id="warranty" >
                                        <option selected disabled>   {{ __('select') }} {{ __('warranty') }} </option>  
                                            @foreach ($warranties as $warranty )
                                                <option value="{{ $warranty->id }}" @if(old('warranty_id',$product->warranty_id) == $warranty->id) selected @endif>{{ @$warranty->name }}</option>
                                            @endforeach 
                                    </select> 
                                    @error('warranty_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
                                <div class="col-lg-6 mt-3">
                                    <label for="category" class="form-label">{{ __('category') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" id="category_id" name="category_id"
                                    data-url="{{ route('product.category') }}"
                                      >
                                        <option  selected disabled>   {{ __('select') }} {{ __('category') }}  </option> 
                                        @if(!isSuperadmin())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if(old('category_id',$product->category_id) == $category->id) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-6 mt-3">
                                    <label for="subcategory" class="form-label">{{ __('subcategory') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" id="subcategory" name="subcategory_id"  data-url="{{ route('product.subcategory') }}">
                                        <option  selected disabled>   {{ __('select') }} {{ __('subcategory') }}  </option> 
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" @if($product->subcategory_id == $subcategory->id) selected @endif> {{ @$subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('subcategory_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                        
                                @if(!isUser())
                                <div class="col-lg-6 mt-3">
                                    <label for="branches" class="form-label">{{ __('branch') }} <span class="text-danger">*</span> </label>
                                    <select class="form-control form--control select2" name="branches[]" id="branch" multiple> 
                                        @foreach ($branches as $branch) 
                                            <option value="{{ $branch->id }}" @if(old('branches',$product->AllBranchesIds) && in_array($branch->id,old('branches',$product->AllBranchesIds))) selected @endif>
                                                {{ $branch->name }}
                                            </option> 
                                        @endforeach
                                    </select>
                                    @error('branches')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                                @endif
                                <div class="col-lg-6 mt-3">
                                    <label for="variation" class="form-label">{{ __('variations') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="variation_id" id="variation_id" data-url="{{ route('product.variation.values') }}"> 
                                        <option selected disabled>{{ __('select') }} {{ __('variation') }}</option>
                                        @foreach ($variations as $variation)
                                            <option value="{{ $variation->id }}" @if(old('variation_id',$product->variation_id) == $variation->id) selected @endif>{{ $variation->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('variation_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>   
                                <div class="col-lg-6 mt-3">
                                    <label for="variation_values" class="form-label">{{ __('variation_values') }}  </label>
                                    <select class="form-control form--control select2" name="variation_values[]" id="variation_values" multiple>
                                        @if(old('variation_values',$singleVariation->value) && !blank(old('variation_values',$variation->value)))
                                            @if(!old('variation_values'))
                                                @foreach (old('variation_values',$singleVariation->value) as $value)
                                                    @if (count($product->productVariations) > 0 && in_array($value,$product->productVariations->pluck('name')->toArray()))
                                                        <option value="{{ @$value }}" selected>{{ @$value }}</option>
                                                    @else
                                                        <option value="{{ @$value }}" >{{ @$value }}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach (old('variation_values',$singleVariation->value) as $value)
                                                    <option value="{{ @$value }}" selected>{{ @$value }}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                    @error('variation_values')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>   
                                <div class="col-lg-2 mt-3">
                                    <label>&nbsp;</label>
                                    <div class="d-flex mt-3">
                                        <input type="checkbox" name="enable_stock" id="manage_stock" @if(old('enable_stock',$product->enable_stock) == \App\Enums\Status::ACTIVE) checked @endif/>
                                        <label for="manage_stock" class="form-label manage_stock_label">{{ __('manage_stock') }} </label>
                                    </div> 
                                </div>  

                                <div class="col-lg-4 mt-3 alert_quantity alert_quantity_toggle " @if(old('enable_stock',$product->enable_stock) == \App\Enums\Status::ACTIVE) style="display:block"  @endif>
                                    <label for="alert_quantity" class="form-label">{{ __('alert_quantity') }} <span class="text-danger">*</span> </label>
                                     <input type="text" class="form-control form--control" value="{{ old('alert_quantity',$product->alert_quantity) }}" name="alert_quantity"/>
                                    @error('alert_quantity')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-4 mt-3  ">
                                    <label for="quantity" class="form-label">{{ __('quantity') }} <span class="text-danger">*</span> </label>
                                     <input type="text" class="form-control form--control" value="{{ old('quantity',@$product->default_quantity) }}" name="quantity"/>
                                    @error('quantity')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
                                <div class="col-lg-4 mt-3  ">
                                    <label for="purchese_price" class="form-label">{{ __('default_purchase_price') }}<span class="text-danger">*</span> </label>
                                     <input type="text" class="form-control form--control" name="default_purchase_price" id="purchese_price" value="{{ old('default_purchase_price',@$product->purchase_price) }}" />
                                    @error('default_purchase_price')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-4 mt-3  ">
                                    <label for="profit_percent" class="form-label">{{ __('profit_percent') }} ( % )  <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form--control" value="{{ old('profit_percent',@$product->profitPercent->profit_percent) }}" name="profit_percent"  id="profit_percent"/>
                                    @error('profit_percent')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  

                                <div class="col-lg-4 mt-3  ">
                                    <label for="selling_price" class="form-label">{{ __('selling_price') }}   <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form--control" value="{{ old('selling_price',@$product->sell_price) }}" name="selling_price"  id="selling_price"/>
                                    @error('selling_price')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
                                
                                <div class="col-lg-6 mt-3  ">
                                    <label for="applicable_tax" class="form-label">{{ __('applicable_tax') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="tax_id" id="applicableTax" data-url="{{ route('product.applicable.tax') }}"> 
                                        <option disabled selected> {{ __('select') }} {{ __('tax') }}</option>
                                        @if(!isSuperadmin())
                                            @foreach ($taxRates as $tax)
                                            <option value="{{ $tax->id }}" @if(old('tax_id',$product->tax_id) == $tax->id) selected @endif>{{ @$tax->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('tax_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>  
 
                                <div class="col-lg-12 mt-3">
                                    <label for="description" class="form-label">{{ __('description') }} </label>
                                    <textarea class="form-control" name="description" id="description" placeholder="{{ __('enter_description') }}" rows="5">{{ old('description',$product->description) }}</textarea> 
                                    @error('description')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
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
@endsection
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/product/duplicate.js"></script> 
@endpush