
<div class="tab-pane @if($request->account_heads) show active @endif " id="v-accounthead" role="tabpanel" aria-labelledby="v-accounthead-tab" tabindex="0">
    <h4 class="card-title overflow-hidden"> 
        {{ __('account_head') }}
        @if(hasPermission('account_head_create'))
            <a  class="btn btn-primary float-right modalBtn" data-url="{{ route('accounts.account.head.create') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-bs-toggle="tooltip" title="{{ __('levels.add') }}"
            data-bs-placement="top" data-title="{{ __('account_head') }} {{ __('create') }}">
                <i class="fa fa-plus"></i> {{ __('add') }}
            </a>
        @endif
    </h4>  
    <!-- Responsive Dashboard Table -->
    <div class=" table-responsive">
        <table class="table accounthead table-striped table-hover text-left">
            <thead>
                <tr class="border-bottom">
                    <th>#</th> 
                    <th>{{ __('name') }}</th>
                    <th>{{ __('note') }}</th> 
                    <th>{{ __('type') }}</th>
                    <th>{{ __('status') }}</th> 
                    <th>{{ __('action') }}</th> 
                </tr>
            </thead>
                <tr class="odd">
                    <td valign="top" colspan="12" class="dataTables_empty">
                        <div class="text-center">
                            <img class="emptyTables" src="{{settings('table_search_image') }}" width="20%" >
                        </div>
                    </td>
                </tr>
            <tbody> 
            </tbody>
        </table>
    </div>
    <!-- Responsive Dashboard Table -->
  
</div>