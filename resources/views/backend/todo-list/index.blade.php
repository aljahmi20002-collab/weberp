@extends('backend.partials.master')
@section('title',__('to_do'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('todo') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('todoList.index') }}">{{ __('to_do') }}</a> </li>
            <li>  {{ __('list') }} </li>
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
                        @if (hasPermission('todo_create'))
                        <a href="{{ route('todoList.create') }}" class="btn  btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('add') }}"
                        data-bs-placement="top">
                        <i class="fa fa-plus"></i> {{ __('add') }}
                        </a>
                        @endif
                    </h4>  
                    <!-- Responsive Dashboard Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="border-bottom text-left">
                                    <th>#</th> 
                                    <th>{{ __('title') }}</th>
                                    <th>{{ __('project') }}</th>
                                    <th>{{ __('file') }}</th> 
                                    <th>{{ __('user') }}</th> 
                                    <th>{{ __('date') }}</th> 
                                    <th>
                                        @if(isUser())
                                            {{ __('status') }} 
                                        @else 
                                            {{ __('total_completed') }}
                                        @endif
                                    </th> 
                                    <th>{{ __('status_update') }}</th> 
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
<input type="hidden" id="get-todolists" data-url="{{ route('todoList.get.all') }}"/>
@endsection

@push('scripts')    
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script> 
    <script src="{{static_asset('backend/assets')}}/js/bootstrap.bundle.min.js"></script>    
    <script src="{{ static_asset('backend') }}/js/todolist/todolist_table.js" ></script> 
 
@endpush
