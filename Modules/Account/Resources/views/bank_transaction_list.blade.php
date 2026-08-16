@extends('backend.partials.master')
@section('title')
    {{ __('bank_transaction') }} {{ __('list') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bank_transaction') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('accounts') }}</a> </li>
            <li> <a href="#">{{ __('bank_transaction') }}</a> </li>
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
                    <!-- Responsive Dashboard Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left">
                            <thead>
                                <tr class="border-bottom">
                                    <th>#</th>  
                                    <th>{{ __('account_details') }}</th> 
                                    <th>{{ __('type') }}</th> 
                                    <th>{{ __('amount') }}</th> 
                                    <th>{{ __('note') }}</th> 
                                    <th>{{ __('document') }}</th> 
                                    <th>{{ __('date') }}</th> 
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
<input type="hidden" id="get-bank-transaction" data-url="{{ route('accounts.account.get.bank.transaction') }}"/>
@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bank_transaction/bank_transaction_table.js" ></script>
@endpush
