@extends('backend.partials.master')
@section('title')
    {{ __('assets') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('assets') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('assets.index') }}">{{ __('assets') }}</a> </li>
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

                            <a href="{{ route('assets.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('assets.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                
                                @if(business())
                                    <div class="col-6">
                                        <label for="branch_id" class="form-label">{{ __('branch') }} <span class="text-danger">*</span></label>
                                        <select class=" form-control form--control select2" name="branch_id" id="branch_id"  >
                                            <option  disabled selected>{{ __('select') }} {{ __('branch') }}</option> 
                                            @foreach ($branches as $branch )
                                                <option  value="{{ $branch->id }}" @if (old('branch_id') == $branch->id) selected @endif>{{  @$branch->name }}</option>
                                            @endforeach 
                                        </select>
                                        @error('branch_id')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif 
       
                                <div class="col-6 ">
                                    <label for="asset_category_id" class="form-label">{{ __('asset_category')}}</label> <span class="text-danger">*</span>
                                    <select name="asset_category_id" id="asset_category_id" class="select2 form-control form--control select2">
                                        <option disabled selected> {{ __('select') }} {{ __('asset_category') }} </option> 
                                        @foreach ($assetCategories as $category)
                                            <option  value="{{ $category->id }}" @if (old('asset_category_id') == $category->id) selected @endif>{{ $category->title }}</option>
                                        @endforeach 
                                    </select>
                                    @error('asset_category_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
   
                                <div class="col-6">
                                    <label for="supplier" class="form-label">{{ __('supplier') }} </label>
                                    <input type="text" name="supplier" class="form-control form--control" id="supplier" value="{{ old('supplier') }}">
                                    @error('supplier')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
  
   
                                <div class="col-6">
                                    <label for="quantity" class="form-label">{{ __('quantity') }} </label>
                                    <input type="text" name="quantity" class="form-control form--control" id="quantity" value="{{ old('quantity') }}">
                                    @error('quantity')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
   
                                <div class="col-6">
                                    <label for="warranty" class="form-label">{{ __('warranty') }}  </label>
                                    <input type="text" name="warranty" class="form-control form--control" id="warranty" value="{{ old('warranty') }}">
                                    @error('warranty')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
  
                                <div class="col-6">
                                    <label for="invoice_no" class="form-label">{{ __('invoice_no') }}  </label>
                                    <input type="text" name="invoice_no" class="form-control form--control" id="invoice_no" value="{{ old('invoice_no') }}">
                                    @error('invoice_no')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
  
                                <div class="col-6">
                                    <label for="amount" class="form-label">{{ __('amount') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" class="form-control form--control" id="amount" value="{{ old('amount') }}">
                                    @error('amount')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
  

                                <div class="col-12 mt-3">
                                    <label for="description" class="form-label">{{ __('description') }} </label>
                                    <textarea  name="description" class="form-control form--control " id="description" >{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-12 mt-5 text-end">
                                    <button type="submit" class="btn btn-sm submit-btn btn-primary "> <i class="fa fa-save"></i> {{__('save')}}</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
 