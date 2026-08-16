<div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <button class="nav-link  @if(!old('asset_category') && !$request->branch_page && !$request->tax_rate && !$request->account_heads && !$request->barcode_settings)  active @endif text-left" id="v-pills-business-tab" data-bs-toggle="pill" data-bs-target="#v-pills-business" type="button" role="tab" aria-controls="v-pills-business" aria-selected="true" data-title="{{ __('business_settings') }}">{{ __('business') }}</button> 
    @if(hasPermission('branch_read'))
        <button class="nav-link @if($request->branch_page) active @endif text-left" id="v-branch-tab" data-bs-toggle="pill" data-bs-target="#v-branch" type="button" role="tab" aria-controls="v-branch" aria-selected="true" data-title="{{ __('branch_list') }}">{{ __('branch') }}</button> 
    @endif
    @if(hasPermission('tax_rate_read'))
        <button class="nav-link text-left @if($request->tax_rate) active @endif" id="v-taxrate-tab" data-bs-toggle="pill" data-bs-target="#v-taxrate" type="button" role="tab" aria-controls="v-taxrate" aria-selected="true" data-title="{{ __('tax_rate_list') }}">{{ __('tax_rate') }}</button>
    @endif
    @if (hasPermission('account_head_read'))
        <button class="nav-link text-left @if($request->account_heads) active @endif" id="v-accounthead-tab" data-bs-toggle="pill" data-bs-target="#v-accounthead" type="button" role="tab" aria-controls="v-accounthead" aria-selected="true" data-title="{{ __('account_head_list') }}">{{ __('account_head') }}</button>
    @endif  
    @if (hasPermission('asset_category_read'))
        <button class="nav-link text-left @if(old('asset_category')) active @endif" id="v-assetcategory-tab" data-bs-toggle="pill" data-bs-target="#v-assetcategory" type="button" role="tab" aria-controls="v-assetcategory" aria-selected="true" data-title="{{ __('asset_category_list') }}">{{ __('asset_category') }}</button>
    @endif  
    <button class="nav-link text-left @if($request->barcode_settings) active @endif" id="v-barcode-tab" data-bs-toggle="pill" data-bs-target="#v-barcode" type="button" role="tab" aria-controls="v-barcode" aria-selected="true" data-title="{{ __('barcode_settings_list') }}">{{ __('barcode_settings') }}</button>
  
</div>
