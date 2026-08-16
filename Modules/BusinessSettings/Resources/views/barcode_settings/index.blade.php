<div class="tab-pane @if($request->barcode_settings) show active @endif " id="v-barcode" role="tabpanel" aria-labelledby="v-barcode-tab" tabindex="0">
 
    <h4 class="card-title overflow-hidden">  
            {{ __('barcode_settings') }} 
            <a href="#"  class="btn btn-primary float-right modalBtn" data-title="{{ __('barcode_settings') }} {{ __('create') }}" data-url="{{ route('settings.barcode.settings.create') }}" data-bs-toggle="modal"  data-bs-target="#dynamic-modal"
            data-bs-placement="top">
                <i class="fa fa-plus"></i> {{ __('add') }}
            </a> 
    </h4> 
    <!-- Responsive Dashboard Table -->
    <div class="table-responsive">
        <table class="table barcodesettings table-striped table-hover">
            <thead>
                <tr class="border-bottom">
                    <th>#</th> 
                    <th>{{ __('name') }}</th>
                    <th>{{ __('paper_width') }}</th> 
                    <th>{{ __('paper_height') }}</th> 
                    <th>{{ __('label_width') }}</th>
                    <th>{{ __('label_height') }}</th>
                    <th>{{ __('label_in_per_row') }}</th> 
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