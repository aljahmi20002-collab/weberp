@extends('backend.partials.master') 
@section('title')
    {{ __('bulk') }} {{ __('product') }}  
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('product') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('import') }}</a> </li>
            <li> {{ __('product') }} </li>
        </ul>
    </div>
@endsection 

@section('maincontent')
<div class="user-panel-wrapper">
    <div class="user-panel-content">
        <div class="row g-4">
            <div class="col-xl-12">
                <div class="dashboard--widget ">  
                    <div class="row">
                        <div class="col-12">
                            <div >
                                <p>{{ __('please_check_this_before_importing_your_file') }}</p>
                                <ul class="list-style-unset">
                                    <li>Uploaded File type must be <span  >.xlsx</span> </li>
                                    <li>The file header must contain -  name, image_link, unit_id, brand_id, category_id, subcategory_id, warranty_id, branch_id, variation_id, variation_value, quantity, alert_quantity, purchase_price, profit_percent, selling_price, tax_id, description</li> 
                                    <li>Name, unit_id, brand_id, category_id, subcategory_id, @if(business()) branch_id,@endif variation_id, variation_value, purchase_price, profit_percent, selling_price, tax_id must be required.</li>
                                    <li><span  >Image link</span> is optional.</li>  
                                    <li>warranty_id, quantity ,alert_quantity, is optional and must be numeric.</li> 
                                    <li> description field is optional.</li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('units') }}</label>
                                        <select class="form-control form--control select2" >
                                            <option>{{ __('select') }} {{ __('unit') }}</option>
                                            @foreach ($units as $unit)
                                                <option >{{ $unit->id }} = {{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('brands') }}</label>
                                        <select class="form-control form--control select2" >
                                            <option>{{ __('select') }} {{ __('brand') }}</option>
                                            @foreach ($brands as $brand)
                                                <option >{{ $brand->id }} = {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('warranties') }}</label>
                                        <select class="form-control form--control select2" >
                                            <option>{{ __('select') }} {{ __('warranty') }}</option>
                                            @foreach ($warranties as $warranty)
                                                <option >{{ $warranty->id }} = {{ $warranty->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('categories') }}</label>
                                        <select class="form-control form--control select2" id="category_id" data-url="{{ route('bulk.import.product.get.subcategory') }}" >
                                           <option>{{ __('select') }} {{ __('category') }}</option>
                                            @foreach ($categories as $category)
                                                <option >{{ $category->id }} = {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('subcategories') }}</label>
                                        <select class="form-control form--control select2" id="subcategory" > 
                                            <option>{{ __('select') }} {{ __('subcategory') }}</option>
                                        </select>
                                    </div>
                                </div> 
                                @if(business())
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('branch') }}</label>
                                        <select class="form-control form--control select2" >
                                            <option>{{ __('select') }} {{ __('branch') }}</option>
                                            @foreach ($branches as $branch)
                                                <option >{{ $branch->id }} = {{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                @endif
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('variations') }}</label>
                                        <select class="form-control form--control select2" id="variation_id" data-url="{{ route('bulk.import.product.get.variation.values') }}" >
                                            <option>{{ __('select') }} {{ __('variation') }}</option>
                                            @foreach ($variations as $variation)
                                                <option value="{{ $variation->id }}" >{{ $variation->id }} = {{ $variation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('variation_values') }}</label>
                                        <select class="form-control form--control select2" id="variation_values" >
                                            <option>{{ __('select') }} {{ __('variation_value') }}</option> 
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 my-2">
                                    <div class="form-group">
                                        <label>{{ __('tax_rates') }}</label>
                                        <select class="form-control form--control select2" >
                                            <option>{{ __('select') }} {{ __('taxrate') }}</option>
                                            @foreach ($taxRates as $taxrate)
                                                <option >{{ $taxrate->id }} = {{ $taxrate->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-6">  
                            
                            @if($errors->any())
                            <ul class="my-3 list-style-unset" >
                                @foreach ($errors->all() as $message) 
                                    <li class="my-2"><p class="text-danger">{{ $message }}</p></li>
                                @endforeach
                            </ul>
                            @endif
                            <form  action="{{ route('bulk.import.product.excel.store') }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="input-group">
                                    <input type="file" class="form-control form--control" name="file"/>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-sm btn-primary bulk-import-group-btn" >{{ __('import') }}</button>   
                                        <a  href="{{ static_asset('backend/bulkimport-sample/product-import-sample.xlsx') }}" download="" class="btn btn-sm btn-success bulk-import-group-btn" >{{ __('download_sample') }}</a>   
                                    </div>
                                  </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ static_asset('backend/js/bulk-excel-import/product.js') }}"></script>
@endpush