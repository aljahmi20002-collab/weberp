@extends('backend.partials.master')
@section('title')
    {{ __('category') }} {{ __('edit') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('categories') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('category.index') }}">{{ __('categories') }}</a> </li>
            <li>  {{ __('edit') }} </li>
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
                                <a href="{{ route('category.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                                data-bs-placement="top">
                                    <i class="fa fa-arrow-left"></i> {{ __('back') }}
                                </a>
                            </h4>
                            <form action="{{ route('category.update',['id'=>$category->id]) }}" method="post" enctype="multipart/form-data" id="category_form">
                                @csrf
                                @method('put')
                                <div class="row"> 
                                    @if(isSuperadmin())
                                        <div class="col-6 mt-3">
                                            <label for="business" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                                            <select class=" form-control form--control select2" name="business_id" id="business_id" data-url="{{ route('category.parent.categories') }}">
                                                <option  disabled selected>{{ __('select') }} {{ __('business') }}</option> 
                                                    @foreach ($businesses as $business )
                                                        <option  value="{{ $business->id }}" @if(old('business_id',$category->business_id)) selected @endif>{{  @$business->business_name }}</option>
                                                    @endforeach 
                                            </select>
                                            @error('business_id')
                                                <p class="text-danger pt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif 
                            
                                    <div class="col-lg-6 mt-3">
                                        <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name',$category->name) }}">
                                        @error('name')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>  
                                    
                                    <div class="col-lg-6 mt-3">
                                        <label for="image" class="form-label">{{ __('image') }} <small  >(png, jpg)</small>   </label>
                                        <input type="file" name="image" class="form-control form--control" id="image" value="{{ old('image') }}">
                                        @error('image')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>  
                            
 
                                    <div class="col-lg-6 mt-3">
                                        <label for="position" class="form-label">{{ __('position') }} </label>
                                        <input type="text" name="position" class="form-control form--control" id="position" value="{{ old('position',$category->position) }}">
                                        @error('position')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-lg-2  mt-4 pt-lg-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex mt-3">
                                                    <label class="form-label cmr-10">{{ __('status') }}</label>
                                                    <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',$category->status) ==\App\Enums\Status::INACTIVE? '':'checked' }} >
                                                    <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                                </div>
                                            </div> 
                                        </div>
                                    </div> 
                                                                
                                    <div class="col-xl-4 mt-4 pt-lg-3"  >
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center permission-check-box pb-2 pt-2"  >
                                                    <input id="parentCategory"  class="read common-key" type="checkbox"  name="parentCategory" @if($category->parent_id !=null) checked @endif />
                                                    <label for="parentCategory" class="permission-check-lebel">{{ __('add_parent') }}</label>
                                                </div>
                                            </div> 
                                        </div>
                                    </div> 

                                                                
                                    <div class="col-xl-6 @if($category->parent_id == null) d-none @endif  pt-lg-3" id="parentDiv">
                                        <div class="row">
                                            <div class="col-12">
                                               <label class="form-label "> {{ __('parent') }}</label> 
                                                <select class="form-control form--control select2" id="parentCategories" name="parent_id">
                                                    <option selected disabled>{{ __('select') }} {{ __('parent_category') }}</option>
                                                    @foreach ($parentCategories as $parent)
                                                        <option value="{{ $parent->id }}" @if($category->parent_id == $parent->id) selected @endif>{{ $parent->name }}</option>
                                                    @endforeach
                                                </select> 
                                            </div> 
                                        </div>
                                    </div> 
 
                                    <div class="col-lg-12 mt-3">
                                        <label for="description" class="form-label">{{ __('description') }} </label>
                                        <textarea name="description" class="form-control" id="description">{{ old('description',$category->description) }}</textarea> 
                                        @error('description')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 mt-5  text-end">
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
    <script src="{{ static_asset('backend') }}/js/category/create.js"></script>
@endpush