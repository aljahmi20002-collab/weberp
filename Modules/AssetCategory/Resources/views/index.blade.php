<div class="tab-pane @if(old('asset_category')) show @endif" id="v-assetcategory" role="tabpanel" aria-labelledby="v-assetcategory-tab" tabindex="0">
 
    <h4 class="card-title overflow-hidden">  
        {{ __('asset_category') }}
        @if(hasPermission('asset_category_create'))
            <a href="#"  class="btn btn-primary float-right modalBtn" data-title="{{ __('asset_category') }} {{ __('create') }}" data-url="{{ route('settings.assetcategory.create') }}" data-bs-toggle="modal"  data-bs-target="#dynamic-modal"
            data-bs-placement="top">
                <i class="fa fa-plus"></i> {{ __('add') }}
            </a>
        @endif
    </h4> 
    <!-- Responsive Dashboard Table -->
    <div class=" ">
        <table class="table assetcategory table-striped table-hover">
            <thead>
                <tr class="border-bottom">
                    <th>#</th> 
                    <th>{{ __('title') }}</th>
                    <th>{{ __('position') }}</th>  
                    <th>{{ __('status') }}</th> 
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
    <!-- Responsive Dashboard Table--> 
</div>