<div class="tab-pane @if($request->todo_list) show active @endif " id="pills-todolist" role="tabpanel" aria-labelledby="pills-todolist-tab" tabindex="0">
    <div class="dashboard--widget height100">
        <!-- Responsive Dashboard Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="border-bottom text-left">
                        <th>#</th> 
                        <th>{{ __('title') }}</th>
                        <th>{{ __('project') }}</th>
                        <th>{{ __('file') }}</th> 
                        <th>{{ __('date') }}</th>  
                        <th>{{ __('status') }}</th>  
                        <th>{{ __('status_update') }}</th> 
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
</div>
<input type="hidden" id="get-user-todolist" data-url="{{ route('user.get.todolist',$user->id) }}"/>