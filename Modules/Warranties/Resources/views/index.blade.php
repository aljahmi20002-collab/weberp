@extends('backend.partials.master')
@section('title',__('warranties'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('warranties') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('warranty.index') }}">{{ __('warranties') }}</a> </li>
            <li class="active">  {{ __('list') }} </li>
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
                            @if(hasPermission('warranty_create'))
                                <a  href="#" class="btn btn-primary float-right modalBtn " data-modalsize="modal-lg" data-title="{{ __('warranty') }} {{ __('create') }}" data-url="{{ route('warranty.create') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4>

                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive category-table">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th>  
                                        <th>{{ __('name') }}</th>
                                        <th>{{ __('duration') }}</th>
                                        <th>{{ __('duration_type') }}</th>
                                        <th>{{ __('description') }}</th> 
                                        <th>{{ __('position') }}</th>  
                                        <th>{{ __('status') }}</th>   
                                         <th>{{ __('action') }}</th>
                                     
                                    </tr>
                                </thead>
                                <tbody  >
                                    <tr class="even">
                                        <td valign="top" colspan="12" class="dataTables_empty">
                                            <div class="text-center">
                                                <img class="emptyTables" src="{{settings('table_search_image') }}" width="20%" >
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Responsive Dashboard Table --> 
                    </div>
                </div>
            </div>
        </div>
    </div> 
   <input type="hidden" id="get-warranties" data-url="{{ route('warranty.get.all') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script> 
    <script src="{{ static_asset('backend') }}/js/warranties/warranties_table.js" ></script> 
@endpush
