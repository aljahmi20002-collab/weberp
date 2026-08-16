<div class="tab-pane @if($request->tax_rate) show active @endif " id="v-taxrate" role="tabpanel" aria-labelledby="v-taxrate-tab" tabindex="0">
 
    <h4 class="card-title overflow-hidden">  
        {{ __('tax_rate') }}
        @if(hasPermission('tax_rate_create'))
            <a href="#"  class="btn btn-primary float-right modalBtn" data-title="{{ __('tax_rate') }} {{ __('create') }}" data-url="{{ route('settings.tax.rate.create') }}" data-bs-toggle="modal"  data-bs-target="#dynamic-modal"
            data-bs-placement="top">
                <i class="fa fa-plus"></i> {{ __('add') }}
            </a>
        @endif
    </h4> 
    <!-- Responsive Dashboard Table -->
    <div class="table-responsive">
        <table class="table taxrate table-striped table-hover">
            <thead>
                <tr class="border-bottom">
                    <th>#</th>
                   
                    <th>{{ __('name') }}</th>
                    <th>{{ __('tax_rate_parcentage') }}</th> 
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