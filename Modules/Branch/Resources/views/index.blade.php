<div class="tab-pane @if($request->branch_page) show active @endif " id="v-branch" role="tabpanel" aria-labelledby="v-branch-tab" tabindex="0">
    <div class="row g-4">
        <div class="col-xl-12"> 
                <h4 class="card-title overflow-hidden"> 
                    {{ __('branch') }}
                    @if(hasPermission('branch_create'))
                        <a href="#"  class="btn btn-primary float-right modalBtn" data-title="{{ __('branch') }} {{ __('create') }}" data-url="{{ route('settings.branch.create') }}" data-modalsize="modal-lg" data-bs-toggle="modal" title="{{ __('levels.add') }}"
                      data-bs-target="#dynamic-modal">
                            <i class="fa fa-plus"></i> {{ __('add') }}
                        </a>
                    @endif
                </h4>
                <!-- Responsive Dashboard Table -->
                <div class=" table-responsive">
                    <table class="table branch table-striped table-hover">
                        <thead>
                            <tr class="border-bottom">
                                <th>#</th>
                                <th>{{ __('name') }}</th>
                                <th>{{ __('email') }}</th>
                                <th>{{ __('website') }}</th>
                                <th>{{ __('phone') }}</th>
                                <th>{{ __('country') }}</th>
                                <th>{{ __('state') }}</th>
                                <th>{{ __('city') }}</th>
                                <th>{{ __('zip_code') }}</th>
                                <th>{{ __('balance') }}</th>
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
                <!-- Responsive Dashboard Table -->
                 
        </div>
    </div> 
</div>