@extends('backend.partials.master')
@section('title')
    @if($request->branch_page):
        {{ __('branch_list') }}
    @elseif ($request->tax_rate_page)
        {{ __('tax_rate_list') }}
    @elseif (old('asset_category'))
        {{ __('asset_category_list')  }}
    @else
        {{ __('business_settings') }}
    @endif
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('business') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('settings') }}</a> </li>
            <li class="active">{{ __('business_settings') }} </li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content"> 
            <div class="row g-4 mt-2">
                <div class="col-md-2">
                    <div class="dashboard--widget  height100 p-0 overflow-hidden"> 
                        @include('businesssettings::layouts.settings_sidebar')
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="dashboard--widget  "> 
                        <div class="tab-content pt-0" id="v-pills-tabContent">
                            @include('businesssettings::settings_forms.business')
                            @if(hasPermission('branch_read'))
                                @include('branch::index')
                            @endif
                            @if(hasPermission('tax_rate_read'))
                                @include('taxrate::index')
                            @endif
                            @if (hasPermission('account_head_read'))
                                @include('accounthead::index')
                            @endif
                            @if (hasPermission('asset_category_read'))
                                @include('assetcategory::index')
                            @endif
                            @include('businesssettings::barcode_settings.index')
                        </div> 
                    </div>
                </div>
            </div> 
        </div>
    </div>  

    <input type="hidden" id="get-business-setting-branch"          data-url="{{ route('settings.business.settings.get.branch') }}"/>
    <input type="hidden" id="get-business-setting-taxrate"         data-url="{{ route('settings.business.settings.get.taxrate') }}"/>
    <input type="hidden" id="get-business-setting-accounthead"     data-url="{{ route('settings.business.settings.get.accounthead') }}"/>
    <input type="hidden" id="get-business-setting-barcodesettings" data-url="{{ route('settings.business.settings.get.barcodesettings') }}"/>
    <input type="hidden" id="get-assetcategories" data-url="{{ route('settings.assetcategory.get.all') }}"/>

@endsection
@push('scripts') 
    <script src="{{ static_asset('backend') }}/js/bootstrap.bundle2.min.js" ></script>
    <script type="text/javascript">
        $('.nav-link').click(function(){
            document.title  = $(this).data('title');
        });
    </script>
    <script src="{{ static_asset('backend') }}/js/business_settings/branch_table.js" ></script>
    <script src="{{ static_asset('backend') }}/js/business_settings/taxrate_table.js" ></script>
    <script src="{{ static_asset('backend') }}/js/business_settings/accounthead_table.js" ></script>
    <script src="{{ static_asset('backend') }}/js/business_settings/barcodesettings_table.js" ></script>
    <script src="{{ static_asset('backend') }}/js/assetcategory/assetcategory_table.js" ></script>
  
@endpush
