@extends('backend.partials.master')
@section('title',__('assets'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('assets') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('assets.index') }}">{{ __('assets') }}</a> </li>
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
                        @if (hasPermission('assets_create'))
                        <a href="{{ route('assets.create') }}" class="btn  btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('add') }}"
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
                                    <th>{{ __('branch') }}</th>
                                    <th>{{ __('asset_category') }}</th>
                                    <th>{{ __('name') }}</th> 
                                    <th>{{ __('invoice_no') }}</th> 
                                    <th>{{ __('supplier') }}</th> 
                                    <th>{{ __('quantity') }}</th>  
                                    <th>{{ __('warranty') }}</th> 
                                    <th>{{ __('amount') }}</th> 
                                    <th>{{ __('description') }}</th> 
                                    <th>{{ __('created_by') }}</th> 
                                    <th>{{ __('action') }}</th> 
                                </tr>
                            </thead>
                            <tbody> 
                                <tr class="odd">
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
<input type="hidden" id="get-assets" data-url="{{ route('assets.get.all') }}"/>
@endsection

@push('scripts')    
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>  
    <script src="{{ static_asset('backend') }}/js/assets/assets_table.js" ></script>  
@endpush
