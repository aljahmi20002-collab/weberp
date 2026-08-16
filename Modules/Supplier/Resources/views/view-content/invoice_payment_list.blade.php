<div class="tab-pane fade @if($request->purchase_payment) show active @endif" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
    <div class="dashboard--widget">
        <div class="  table-responsive category-table product-view-table mt-2">
            <table class="table purchase-invoice-payment table-striped table-hover">
                <thead>
                    <tr class="border-bottom  align-middle">
                        <th>#</th>
                        <th>{{ __('date') }}</th>
                        <th>{{ __('invoice_no') }}</th>
                        <th>{{ __('payment_info') }}</th> 
                        <th>{{ __('amount') }}</th> 
                        <th>{{ __('document') }}</th> 
                        <th>{{ __('description') }}</th>   
                        <th>{{ __('action') }} </th>  
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

      

    </div>

</div>  