    <!-- Responsive Dashboard Table -->
    <div class="table-responsive">
    <table class="table rejected-applied-leave table-striped table-hover">
        <thead>
            <tr class="border-bottom">
                <th>#</th>
                <th>{{ __('applicant') }}</th>
                <th>{{ __('leave_type') }}</th>
                <th>{{ __('leave_from') }}</th>
                <th>{{ __('leave_to') }}</th>
                <th>{{ __('file') }}</th>
                <th>{{ __('reason') }}</th>
                <th>{{ __('status') }}</th>
                <th>{{ __('submited') }}</th> 
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
