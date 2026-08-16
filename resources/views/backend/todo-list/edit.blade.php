@extends('backend.partials.master')
@section('title')
    {{ __('to_do') }} {{ __('edit') }}
@endsection 
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('todo') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('todoList.index') }}">{{ __('to_do') }}</a> </li>
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
                            <a href="{{ route('todoList.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('todoList.update',$todoList->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row"> 
                                @if(isSuperadmin())
                                    <div class="col-6">
                                        <label for="business" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                                        <select class=" form-control form--control select2" name="business_id" id="business_id" data-url="{{ route('todoList.business.users') }}">
                                            <option  disabled selected>{{ __('select') }} {{ __('business') }}</option>
                                            @foreach ($businesses as $business )
                                                <option  value="{{ $business->id }}" @if($business->id == $todoList->business_id ) selected @endif>{{  @$business->business_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('business_id')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif 
                                <div class="col-6">
                                    <label for="title" class="form-label">{{ __('title') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form--control" id="title" value="{{ old('title',$todoList->title) }}">
                                    @error('title')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-6 ">
                                    <label for="project_id" class="form-label">{{ __('project')}}</label> <span class="text-danger">*</span>
                                    <select name="project_id" id="project_id" class="select2 form-control form--control select2">
                                        <option disabled selected> {{ __('select') }} {{ __('project') }} </option> 
                                        @foreach ($projects as $project)
                                            <option  value="{{ $project->id }}" @if($todoList->project_id == $project->id) selected @endif>{{ $project->title }}</option>
                                        @endforeach 
                                    </select>
                                    @error('project_id')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                <div class="col-6 ">
                                    <label for="user_id" class="form-label">{{ __('Users')}}</label> <span class="text-danger">*</span>
                                    <select name="user_id[]" id="user_id" class="select2 form-control form--control " multiple>  
                                        @foreach ($users as $user)
                                            <option  value="{{ $user->id }}" @if(in_array($user->id,$tdassignedUsers != null? $tdassignedUsers->toArray():[])) selected @endif>{{ $user->name }}</option>
                                        @endforeach 
                                    </select>
                                    @error('user_id')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="date" class="form-label">{{ __('date') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="date" class="form-control form--control dateTodo" id="date" value="{{ old('date',$todoList->date) }}" readonly>
                                    @error('date')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                <div class="col-6 mt-3">
                                    <label for="todoFile">{{ __('File') }}</label>
                                    <input id="todoFile" type="file" name="todoFile" data-parsley-trigger="change" placeholder="Enter file" autocomplete="off" class="form-control form--control">
                                    @error('todoFile')
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                    @enderror
                                </div> 
                                <div class="col-12 mt-3">
                                    <label for="description" class="form-label">{{ __('description') }} </label>
                                    <textarea  name="description" class="form-control form--control " id="description" >{{ old('description',$todoList->description) }}</textarea>
                                    @error('description')
                                    <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div> 
                                <div class="col-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
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
            $('.dateTodo').datepicker({
                changeMonth:true,
                dateFormat: 'dd/mm/yy',
                yearRange: year,
                changeYear:true,
            });
        });
    </script>
    <script src="{{static_asset('backend/js')}}/todolist/create.js"></script>
@endpush
