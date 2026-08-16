

@extends('backend.partials.master')
@section('title')
    {{ __('labels') }} {{ __('print') }} - {{ business_name(Auth::user()->id) }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('labels') }} {{ __('print') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('product.index') }}">{{ __('product') }}</a> </li>
            <li>  {{ __('labels') }} </li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <b>{{ __('product') }}:</b> {{ @$product->name }}

                        <h5 class="mt-2">{{ __('variations') }}:</h5>
                        <form  id="labelPrintForm" action="{{ route('product.label.print',['id'=>$product->id]) }}" method="post" target="_blank" >
                            @csrf
                            <div class="table-responsive table-responsive category-table product-view-table mt-2 labelprinttable">
                                <table class="table table-striped table-hover text-left">
                                    <thead>
                                        <tr class="border-bottom bg-primary text-white head">
                                            <th>#</th>
                                            <th>{{ __('variation') }}</th>
                                            <th>{{ __('sku') }}</th> 
                                            <th>{{ __('selling_price_inc_tax') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($product->productVariations as $variation )            
                                        <tr>
                                            <td><input type="checkbox"   value="{{ $variation->id }}" name="product_variation[]" /></td>
                                            <td>{{ @$variation->variation->name }} - {{ $variation->name }}</td>
                                            <td> {{ @$variation->sub_sku }}</td>
                                            <td>{{ @businessCurrency($variation->product->business_id) }} {{ @$variation->sell_price_inc_tax }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @error('product_variation')
                                <div class="mt-3 text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <h5 class="mt-2">{{ __('font_size') }}:</h5>
                             <div class="row labelfontsize">
                                <div class="col-lg-2">
                                    <label class="d-flex">{{ __('business_name') }} <input type="checkbox" name="business_name_show" checked/></label>
                                    <input type="text" name="business_name" class="form-control form--control" value="12"/>
                                </div>
                                <div class="col-lg-2">
                                    <label class="d-flex">{{ __('product_name') }}<input type="checkbox" name="product_name_show" checked/></label>
                                    <input type="text" name="product_name" class="form-control form--control" value="12"/>
                                </div>
                                <div class="col-lg-2">
                                    <label class="d-flex">{{ __('variation_name') }}<input type="checkbox" name="variation_name_show" checked/></label>
                                    <input type="text" name="variation_name" class="form-control form--control" value="12"/>
                                </div>
                                <div class="col-lg-2">
                                    <label class="d-flex">{{ __('product_price') }}<input type="checkbox" name="product_price_show" checked/></label>
                                    <input type="text" name="product_price" class="form-control form--control" value="12"/>
                                </div>
                                <div class="col-lg-2">
                                    <label  >{{ __('barcode_scale') }} </label>
                                    <input type="text" name="barcode_scale" class="form-control form--control" value="1"/>
                                </div>
                             </div>
                             <h5 class="mt-2">{{ __('label_settings') }}:</h5>
                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <select class="form-control form--control" name="label_settings">
                                        @foreach ($barcode_settings as $barcode)
                                            <option value="{{ $barcode->id }}">{{ $barcode->name }}, Seet size: {{ $barcode->paper_weight }} x {{ $barcode->paper_height }}, Label size: {{ $barcode->label_weight}} x {{ $barcode->label_height }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div  class="mt-3">
                                <button type="submit" class="btn btn-sm btn-primary">{{ __('print') }}</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
  
@endsection
