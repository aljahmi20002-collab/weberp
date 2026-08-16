@extends('backend.partials.master')
@section('title')
    {{ __('plan') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('plans') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('plans.index') }}">{{ __('plans') }}</a> </li>
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

                            <a href="{{ route('plans.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('plans.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row"> 
                                    <div class="col-lg-6 mt-3">
                                        <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                                        @error('name')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>   
                                    <div class="col-lg-6 mt-3">
                                        <label for="user_count" class="form-label">{{ __('user_count') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="user_count" class="form-control form--control" id="user_count" value="{{ old('user_count') }}">
                                        @error('user_count')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mt-3">
                                        <label for="days_count" class="form-label">{{ __('days_count') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="days_count" class="form-control form--control" id="days_count" value="{{ old('days_count') }}">
                                        @error('days_count')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mt-3">
                                        <label for="price" class="form-label">{{ __('price') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="price" class="form-control form--control" id="price" value="{{ old('price') }}">
                                        @error('price')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                   


                                    <div class="col-lg-6 mt-3">
                                        <label for="description" class="form-label">{{ __('description') }} </label>
                                        <input  name="description" class="form-control form--control " id="description" /> 
                                        @error('description')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mt-3">
                                        <label for="position" class="form-label">{{ __('position') }} </label>
                                        <input type="text" name="position" class="form-control form--control" id="position" value="{{ old('position') }}">
                                        @error('position')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-lg-3   mt-4 pt-lg-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex mt-3">
                                                    <label class="form-label cmr-10">{{ __('status') }}</label>
                                                    <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                                    <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12 mt-3 plan-modules">
                                        <label class="form-label cmr-10">{{ __('select_modules') }}</label>
                                        <div class="d-flex border-bottom align-items-center permission-check-box pb-2 pt-2"  >
                                            <input id="selectAllModules"   class="read common-key" type="checkbox"/>
                                            <label for="selectAllModules"  class="permission-check-lebel">{{ __('select_all') }}</label>
                                        </div>
                                        <div class="row check-module mt-3">
                                            @foreach ($modules as $module)                                                
                                                <div class="col-lg-2 col-md-4 col-sm-6">
                                                    <div class="d-flex align-items-center permission-check-box pb-2 pt-2"  >
                                                        <input id="{{ @$module }}"  class="read module common-key" type="checkbox" value="{{ @$module }}" name="modules[]"  />
                                                        <label for="{{ @$module }}" class="permission-check-lebel">{{ __(@$module) }}</label>
                                                    </div>
                                                </div> 
                                            @endforeach
                                        </div>
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
    <script src="{{ static_asset('backend') }}/js/plans/create.js"></script>
@endpush