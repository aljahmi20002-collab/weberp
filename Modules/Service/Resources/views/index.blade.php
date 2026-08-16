@extends('backend.partials.master')
@section('title',__('services'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('services') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('services.index') }}">{{ __('services') }}</a> </li>
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
                            @if(hasPermission('service_create'))
                                <a href="{{ route('services.create') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                    data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4>  
                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive plan-table"> 
                            <table class="table  table-striped table-hover text-left  " style="width:100%"> 
                                <thead>
                                    <tr class="border-bottom">  
                                        <th>{{ __('#') }}</th>  
                                        <th>{{ __('name') }}</th>   
                                        <th class="w-150px">{{ __('price') }}</th>   
                                        <th>{{ __('description') }}</th>   
                                        <th>{{ __('position') }}</th>   
                                        <th>{{ __('status') }}</th>  
                                        <th>{{ __('action') }}</th> 
                                    </tr>
                                </thead>
                                <tbody> 
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
    <input type="hidden" id="get-service" data-url="{{ route('services.data') }}"/>
@endsection
 
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script src="{{ static_asset('backend') }}/js/service/service.js" ></script>
@endpush
