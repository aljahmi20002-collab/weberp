@extends('backend.partials.master')
@section('title') 
    @if(business())
        {{ __('support') }} {{ __('create') }}
    @else
        {{ __('submit_a_request') }}
    @endif 
@endsection 
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">
            {{ @$title }}
        </h5>
        <ul class="breadcrumb">
            <li> <a href="#"> {{ @$title }} </a> </li> 
            <li>
                @if(business())
                    {{ __('create') }}
                @else
                    {{ __('submit_a_request') }}
                @endif
            </li>
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

                            <a href="{{ route('support.index') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('support.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">  
                                @if(business())
                                    <div class="{{ business() == true? 'col-lg-6':'col-lg-4' }}  mt-3">
                                        <label for="user_id" class="form-label">{{ __('employee') }} <span class="text-danger">*</span></label>
                                        <select class="form-control form--control select2" id="user_id" name="user_id">
                                            <option selected disabled>{{ __('select') }} {{ __('employee') }}</option>
                                            @foreach ($users as $user )
                                               @if ($user->id != Auth::user()->id) 
                                                <option value="{{ @$user->id }}" @if(old('user_id') == $user->id) selected @endif>{{ $user->name }}</option>
                                               @endif
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div> 
                                @endif 

                                <div class="{{ business() == true? 'col-lg-6':'col-lg-4' }} mt-3">
                                    <label for="service_id" class="form-label">{{ __('service') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" id="service_id" name="service_id">
                                        <option selected disabled>{{ __('select') }} {{ __('service') }}</option>
                                        @foreach ($services as $service )
                                            <option value="{{ @$service->id }}" @if(old('service_id') == $service->id) selected @endif>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                
                                <div class="{{ business() == true? 'col-lg-6':'col-lg-4' }}  mt-3">
                                    <label for="department_id" class="form-label">{{ __('department') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" id="department_id" name="department_id">
                                        <option selected disabled>{{ __('select') }} {{ __('department') }}</option>
                                        @foreach ($departments as $department )
                                            <option value="{{ @$department->id }}" @if(old('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                <div class="{{ business() == true? 'col-lg-6':'col-lg-4' }}  mt-3">
                                    <label for="priority" class="form-label">{{ __('priority') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" id="priority" name="priority">
                                        <option selected disabled>{{ __('select') }} {{ __('priority') }}</option>  
                                           @foreach (\Config::get('pos_default.priority') as $key=>$priority ) 
                                                <option value="{{$priority }}" @if(old('priority') == $priority ) selected @endif>{{ __($priority) }}</option> 
                                           @endforeach
                                    </select>
                                    @error('priority')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 

                                <div class="col-lg-12 mt-3">
                                    <label for="subject" class="form-label">{{ __('subject') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control form--control" id="subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 

                                <div class="col-lg-12 mt-3">
                                    <label for="description" class="form-label">{{ __('description') }} <span class="text-danger">*</span></label>
                                     <textarea class="form-control form--control" name="description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 

                                <div class="col-lg-12 mt-3">
                                    <label for="attached_file" class="form-label">{{ __('attached_file') }} </label>
                                     <input class="form-control form--control" name="attached_file" type="file"/>
                                     
                                </div> 
 
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> 
                                        @if(business())
                                            {{ __('create') }}
                                        @else
                                            {{ __('submit_a_request') }}
                                        @endif    
                                    </button>
                                </div>  
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
