@extends('backend.partials.master')
@section('title',__('to_do'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('todo') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('todoList.index') }}">{{ __('to_do') }}</a> </li>
            <li>  {{ __('view') }} </li>
        </ul>
    </div>
@endsection

@section('maincontent')
<div class="user-panel-wrapper">
    <div class="user-panel-content">
        <div class="row g-4">
            <div class="col-xl-12">
                <div class="card"> 
                    <ul class="nav nav-pills " id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link @if(!$request->project) active @endif" id="pills-todo-tab" data-bs-toggle="pill" data-bs-target="#pills-todo" type="button" role="tab" aria-controls="pills-todo" aria-selected="true">{{ __('todo_list') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link @if($request->project) active @endif" id="pills-project-tab" data-bs-toggle="pill" data-bs-target="#pills-project" type="button" role="tab" aria-controls="pills-project" aria-selected="false">{{ __('project') }}</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane @if(!$request->project) show  active @endif" id="pills-todo" role="tabpanel" aria-labelledby="pills-todo-tab" tabindex="0">
                       
                            <div class="row">
                                <div class="col-xl-6 mt-2"> 
                                    <div class="dashboard--widget height100 mb-3"> 
                                        <h4 class="card-title overflow-hidden">{{ __('todo_list_details') }}</h4> 
                                        <div class="row mt-2">
                                            <div class="col-xl-6 col-sm-4"><b>{{ __('title') }}:</b></div>
                                            <div class="col-xl-6 col-sm-8">{{ $todolist->title}}</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xl-6 col-sm-4"><b>{{ __('date') }}:</b></div>
                                            <div class="col-xl-6 col-sm-8">{{ dateFormat($todolist->date)}}</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xl-6 col-sm-4"><b>{{ __('file') }}:</b></div>
                                            <div class="col-xl-6 col-sm-8"><a href="{{ $todolist->file }}" download="" >{{ __('download') }}</a></div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xl-6 col-sm-4"><b>{{ __('status') }}:</b></div>
                                            <div class="col-xl-6 col-sm-8">{!! $todolist->my_status!!}</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xl-12"><b>{{ __('description') }}:</b></div>
                                            <div class="col-xl-12">{!! $todolist->description !!}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mt-2">
                                    <div class="dashboard--widget height100 mb-3"> 
                                        <h4 class="card-title overflow-hidden">{{ __('assigned_to') }}</h4>  
                                        <div class="row">
                                                @foreach ($todolist->todolistAssigned as $assigned)
                                               
                                                    <div class="col-3 col-lg-2 mt-3"
                                                    > 
                                                    <div
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" 
                                                        data-bs-html="true"
                                                        data-bs-title="<b>{{ $assigned->user->name }}</b> <br> <i> <small>{{ $assigned->user->email }}</small></i>"
                                                        >
                                                        <a href="{{ route('user.view',$assigned->user_id) }}">
                                                            <img src="{{ $assigned->user->image }}" class="@if($assigned->status == \App\Enums\TodoStatus::COMPLETED) bdr-success @elseif($assigned->status == \App\Enums\TodoStatus::PROCESSING) bdr-precessing @endif w-100 responsive rounded-circle" />
                                                        </a>
                                                    </div>

                                                        @if(hasPermission('todo_statusupdate') )
                                                            @if(business() || Auth::user()->id == $assigned->user->id)
                                                                <div data-title="{{ __('status_update') }}" class="text-center mt-2">
                                                                    @if($assigned->status == \App\Enums\TodoStatus::COMPLETED)
                                                                        {!! $assigned->my_status !!} 
                                                                    @else
                                                                        <div class="dropdown custom-dropdowns ">
                                                                            <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fa fa-ellipsis"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                <a class="dropdown-item" href="{{ route('todoList.status.update',[$todolist->id,'user_id'=>$assigned->user_id]) }}">
                                                                                    @if( $assigned->status == \App\Enums\TodoStatus::PENDING)
                                                                                        {{ __('processing') }}
                                                                                    @elseif($assigned->status == \App\Enums\TodoStatus::PROCESSING)
                                                                                        {{ __('completed') }}
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <div class="tab-pane @if($request->project) show  active @endif" id="pills-project" role="tabpanel" aria-labelledby="pills-project-tab" tabindex="0">   
                        <div class="row">
                            <div class="col-xl-6 mt-2"> 
                                <div class="dashboard--widget height100 mb-3"> 
                                    <h4 class="card-title overflow-hidden">{{ __('project_details') }}</h4> 
                                    @if(business())
                                    <div class="row mt-2">
                                        <div class="col-xl-6 col-sm-4"><b>{{ __('branch') }}:</b></div>
                                        <div class="col-xl-6 col-sm-8">{{ @$todolist->project->branch->name}}</div>
                                    </div>
                                    @endif
                                    <div class="row mt-2">
                                        <div class="col-xl-6 col-sm-4"><b>{{ __('title') }}:</b></div>
                                        <div class="col-xl-6 col-sm-8">{{ $todolist->project->title}}</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-xl-6 col-sm-4"><b>{{ __('date') }}:</b></div>
                                        <div class="col-xl-6 col-sm-8">{{ dateFormat($todolist->project->date)}}</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-xl-6 col-sm-4"><b>{{ __('file') }}:</b></div>
                                        <div class="col-xl-6 col-sm-8"><a href="{{ $todolist->project->uploaded_file }}" download="" >{{ __('download') }}</a></div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="dashboard--widget height100 mb-3"> 
                                    <h4 class="card-title overflow-hidden">{{ __('description') }}</h4>  
                                    {!! @$todolist->project->description !!}
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')    
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script> 
<script src="{{static_asset('backend/assets')}}/js/bootstrap.bundle.min.js"></script>    
@endpush
