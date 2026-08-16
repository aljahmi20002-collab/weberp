@extends('backend.partials.master')
@section('title',__('subscriptions'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('subscriptions') }}</h5>
        <ul class="breadcrumb">
            <li > <a href="#">{{ __('subscriptions') }}</a>  </li> 
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
                            @if(hasPermission('subscription_change'))
                                <a href="{{ route('subscription.change') }}" class="btn btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('change_subscription') }}"
                                data-bs-placement="top">
                                    <i class="fa fa-plus"></i> {{ __('change_subscription') }}
                                </a>
                            @endif
                        </h4>
 
                        <!-- Responsive Dashboard Table -->
                        <div class=" table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>#</th> 
                                        <th>{{ __('business') }}</th> 
                                        <th>{{ __('plan') }}</th>
                                        <th>{{ __('start_date') }}</th> 
                                        <th>{{ __('end_date') }}</th>   
                                        <th>{{ __('price') }}</th>   
                                        <th>{{ __('paid_via') }}</th>   
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
<input type="hidden" id="get-subscriptions" data-url="{{ route('subscription.get.all.subscription') }}"/>
@endsection
 @push('scripts')
     <script src="{{ static_asset('backend') }}/js/admin_subscription/subscription_table.js"></script>
 @endpush