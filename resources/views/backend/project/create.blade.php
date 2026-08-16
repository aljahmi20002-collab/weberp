@extends('backend.partials.master')
@section('title')
    {{ __('project') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('project') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('project.index') }}">{{ __('project') }}</a> </li>
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

                            <a href="{{ route('project.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('project.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row"> 
                                    @if(isSuperadmin())
                                        <div class="col-6 mt-3">
                                            <label for="title" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                                            <select class=" form-control form--control select2" id="business_id" name="business_id" data-url="{{ route('project.business.branches') }}">
                                                <option  disabled selected>{{ __('select') }} {{ __('business') }}</option>
                                                @foreach ($businesses as $business )
                                                    <option  value="{{ $business->id }}">{{  @$business->business_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('business_id')
                                                <p class="text-danger pt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                                                      
                                    @if(isSuperadmin() || business())
                                        <div class="col-6 mt-3">
                                            <label for="branch_id" class="form-label">{{ __('branch')}}</label> <span class="text-danger">*</span>
                                            <select name="branch_id" id="branch_id" class="select2 form-control form--control">
                                                <option disabled selected> {{ __('select') }} {{ __('branch') }} </option>
                                                @if(business())
                                                    @foreach ($branches as $branch)
                                                        <option  value="{{ $branch->id }}" @if(old('branch_id') == $branch->id) selected @endif>{{ $branch->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('branch_id')
                                                <p class="text-danger pt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif


                                    <div class="col-6 mt-3">
                                        <label for="title" class="form-label">{{ __('title') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control form--control" id="title" value="{{ old('title') }}">
                                        @error('title')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-6 mt-3">
                                        <label for="date" class="form-label">{{ __('date') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="date" class="form-control form--control project-date" id="date" value="{{ old('date',Date('d/m/Y')) }}" readonly>
                                        @error('date')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div> 
                                    <div class="col-6 mt-3">
                                        <label for="file" class="form-label">{{ __('file') }}</label>
                                        <input type="file" name="file" class="form-control form--control" id="file" readonly>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label for="description" class="form-label">{{ __('description') }} </label>
                                        <textarea  name="description" class="form-control form--control " id="description" >{{ old('description') }}</textarea>
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
    <script type="text/javascript">
        $(document).ready(function(){
            var year = '1901:'+new Date().getFullYear();
            $('.project-date').datepicker({
                changeMonth:true,
                dateFormat: 'dd/mm/yy',
                yearRange: year,
                changeYear:true,
            });
        });
    </script> 
    <script src="{{ static_asset('backend') }}/js/project/create.js"></script>
@endpush
