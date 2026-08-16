<!-- Responsive Dashboard Table -->
<div class="table-responsive category-table">
    <table class="table table-striped table-hover">
        <thead>
            <tr class="border-bottom">
                <th>#</th>   
                <th>{{ __('sku') }}</th>    
                <th>{{ __('product') }}</th>   
                <th>{{ __('branch') }}</th>  
                <th>{{ __('variation') }}</th>   
                <th>{{ __('category') }}</th>   
                <th>{{ __('purchase_price') }}</th>  
                <th>{{ __('selling_price') }} <br>(Inc.Tax)</th>  
                <th>{{ __('current_stock_selling_price') }}<br> (Inc.Tax)</th>  
                <th>{{ __('profit_percent') }} (%)</th>  
                <th>{{ __('available_quantity') }}</th>   
            </tr>
        </thead>  
        <tbody > 
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
 