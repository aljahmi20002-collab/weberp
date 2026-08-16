@extends('backend.partials.master')
@section('title',__('suppliers'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('suppliers') }}</h5>
        <ul class="breadcrumb"> 
            <li> <a href="{{ route('suppliers.index') }}">{{ __('suppliers') }}</a> </li>
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
                            @if(hasPermission('supplier_create'))
                                <a href="{{ route('suppliers.create') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
                                    data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('add') }}
                                </a>
                            @endif
                        </h4>  
                        <!-- Responsive Dashboard Table -->
                        <div class="table-responsive plan-table">
                            <table class="table table-striped table-hover text-left">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th> 
                                        @if(isSuperadmin())
                                        <th>{{ __('business') }}</th> 
                                        @endif
 
                                        <th>{{ __('supplier') }}</th>   
                                        <th>{{ __('opening_balance') }}</th>
                                        <th>{{ __('balance') }}</th>
                                        <th>{{ __('status') }}</th> 
                                        <th>{{ __('action') }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td data-title="#">{{ ++$i }}</td>
                                            @if(isSuperadmin())
                                            <td data-title="{{ __('business') }}">{{ @$supplier->business->business_name }}</td> 
                                            @endif 
                                            <td data-title="{{ __('supplier') }}"> 
                                                <a href="{{ route('suppliers.view',$supplier->id) }}" class="text-primary">
                                                    {{ __('name') }}    : {{ @$supplier->name }}</a> <br/>
                                                    {{ __('company_name') }}: {{ @$supplier->company_name }} <br/>
                                                    {{ __('email') }}   : {{ @$supplier->email }}
                                                
                                            </td>  
                                            <td data-title="{{ __('opening_balance') }}">{{ businessCurrency($supplier->business_id) }} {{@$supplier->opening_balance }} </td>
                                            <td data-title="{{ __('balance') }}">{{ businessCurrency($supplier->business_id) }} {{@$supplier->balance }} </td>
                                            <td data-title="{{ __('status') }}"> {!! @$supplier->my_status !!} </td>
                                           
                                            <td data-title="Action"> 

                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cogs"></i>
                                                    </a>
                                                    <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">                                                       
                                                        @if(hasPermission('supplier_status_update'))
                                                        <a class="dropdown-item" href="{{ route('suppliers.status.update',$supplier->id) }}">
                                                           {!! $supplier->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>'  !!} {{ @statusUpdate($supplier->status) }}
                                                        </a>
                                                        @endif  
                                                        <a href="{{ route('suppliers.view',@$supplier->id) }}" class="dropdown-item"   ><i class="fas fa-eye"></i>{{ __('view') }}</a>
                                                        @if(hasPermission('supplier_update'))
                                                            <a href="{{ route('suppliers.edit',@$supplier->id) }}" class="dropdown-item"   ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                                                        @endif
                                                        @if(hasPermission('supplier_delete'))
                                                            <form action="{{ route('suppliers.delete',@$supplier->id) }}" method="post" class="delete-form" id="delete" data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_supplier') }}">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="submit" class="dropdown-item"   >
                                                                    <i class="fas fa-trash-alt"></i> {{__('delete')}}
                                                                </button>
                                                            </form>
                                                        @endif 
                                                    </div>
                                                </div>
 
                                            </td> 
                                        </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>
                        <!-- Responsive Dashboard Table -->
                        <!-- Pagination -->
                        <div class="d-flex flex-row-reverse align-items-center pagination-content">
                            <span class="paginate">{{ $suppliers->links() }}</span>
                            <p class="p-2 small paginate">
                                {!! __('Showing') !!}
                                <span class="font-medium">{{ $suppliers->firstItem() }}</span>
                                {!! __('to') !!}
                                <span class="font-medium">{{ $suppliers->lastItem() }}</span>
                                {!! __('of') !!}
                                <span class="font-medium">{{ $suppliers->total() }}</span>
                                {!! __('results') !!}
                            </p>
                        </div>
                        <!-- Pagination -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
@endpush
