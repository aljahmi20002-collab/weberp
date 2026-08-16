@extends('backend.partials.master')
@section('title',__('user_view'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('user') }} {{ __('view') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('user.index') }}">{{ __('user') }}</a> </li>
            <li class="active">  {{ __('view') }} </li>
        </ul>
    </div>
@endsection

@section('maincontent') 
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
                <div class="card">
                    <ul class="nav nav-pills " id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if(!$request->todo_list) active @endif" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" data-title="{{ __('profile') }}">{{ __('profile') }}</button>
                        </li>  
                        @if(!isSuperadmin())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($request->todo_list) active @endif" id="pills-todolist-tab" data-bs-toggle="pill" data-bs-target="#pills-todolist" type="button" role="tab" aria-controls="pills-todolist" aria-selected="true" data-title="{{ __('todo_list') }}">{{ __('todo_list') }}</button>
                        </li>  
                        <li class="nav-item" role="presentation">
                            <button class="nav-link  " id="pills-attendance-tab" data-bs-toggle="pill" data-bs-target="#pills-attendance" type="button" role="tab" aria-controls="pills-attendance" aria-selected="true" data-title="{{ __('attendance') }}">{{ __('attendance') }}</button>
                        </li>  
                        @endif
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent"> 
                    @include('backend.user.view-content.profile') 
                    @if(!isSuperadmin())
                        @include('backend.user.view-content.todo_list') 
                        @include('backend.user.view-content.attendance') 
                    @endif
                </div>  
            </div>
        </div>
    </div>
@endsection 

@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>  
<script src="{{ static_asset('backend') }}/js/user/todo_table.js" ></script>   
@endpush
