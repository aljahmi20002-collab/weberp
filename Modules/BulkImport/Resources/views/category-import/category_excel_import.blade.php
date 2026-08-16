@extends('backend.partials.master') 
@section('title')
    {{ __('bulk') }} {{ __('category') }}  
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('category') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('import') }}</a> </li>
            <li> {{ __('category') }} </li>
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
                        <div class="col-6"> 
                            <div >
                                <p>{{ __('please_check_this_before_importing_your_file') }}</p>
                                <ul class="list-style-unset">
                                    <li>Uploaded File type must be <span >.xlsx</span> </li>
                                    <li>The file header must contain - <span >name , description, position, category_id</span></li>
                                    <li><span >Name</span> must be required.</li>
                                    <li><span >Position</span> must be numeric.</li>
                                    <li><span >Category_id</span> must be numeric and it is parent category id.</li>
                                </ul>
                            </div>
                            @if($errors->any())
                            <ul class="my-3 list-style-unset">
                                @foreach ($errors->all() as $message) 
                                    <li class="my-2"><p class="text-danger">{{ $message }}</p></li>
                                @endforeach
                            </ul>
                            @endif
                            <form  action="{{ route('bulk.import.category.excel.store') }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="input-group">
                                    <input type="file" class="form-control form--control" name="file"/>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-sm btn-primary bulk-import-group-btn" >{{ __('import') }}</button>   
                                        <a  href="{{ static_asset('backend/bulkimport-sample/category-import-sample.xlsx') }}" download="" class="btn btn-sm btn-success bulk-import-group-btn" >{{ __('download_sample') }}</a>   
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