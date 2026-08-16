@extends('backend.partials.master')
@section('title',__('attendance'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('attendance') }}</h5>
        <ul class="breadcrumb">
            <li >  <a href="#">{{ __('hrm') }} </a> </li>
            <li> <a href="#">{{ __('attendance') }}</a></li>
            <li class="active">  {{ __('list') }} </li>
        </ul>
    </div>
@endsection

@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content ">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget  attendance-table"> 
                        <nav> 
                            <form action="{{ route('hrm.attendance.filter') }}" method="get">
                                @csrf
                                <div class="row align-items-center"> 
                                    <div class="col-lg-6 mt-3">
                                        <label for="employee" class="form-label">{{ __('employee') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="search" placeholder="{{ __('enter_name') }}" class="form-control form--control" value="{{ $request->search }}"/>
                                    </div> 
                                    <div class="col-lg-3 mt-5">
                                        <label   class="form-label"><br/></label>
                                        <button type="submit" class="btn btn-sm btn-primary pb-5" >{{ __('get_report') }}</button>
                                    </div>
                                </div>
    
                            </form> 
                          </nav>
                          <div class="tab-content" id="nav-tabContent">
                            @include('attendance::attendance_content.attendance_summery') 
                         </div> 
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection 
@push('scripts') 
<script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
@endpush
