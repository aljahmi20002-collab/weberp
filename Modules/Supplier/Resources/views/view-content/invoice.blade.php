<div class="tab-pane fade @if($request->supplier_invoice) show active @endif" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
    
    <div class="dashboard--widget">
        <!-- Responsive Dashboard Table -->
        <div class=" table-responsive category-table">
            <table class="table purchase-invoice table-striped table-hover text-left">
                <thead>
                    <tr class="border-bottom">
                        <th>#</th>  
                        <th>{{ __('date') }}</th>  
                        <th>{{ __('invoice_no') }}</th>   
                        <th>{{ __('branch') }}</th>  
                        <th>{{ __('payment_status') }}</th>   
                        <th>{{ __('total') }}</th>  
                        <th>{{ __('action') }}</th>   
                    </tr>
                </thead>  
                <tbody > 
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
