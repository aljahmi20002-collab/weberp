@php
    $i=0;
@endphp
@if(count($stock_transfers) >0)
    @foreach ($stock_transfers as $stock_transfer)                                        
    <tr> 
        <td data-title="#">{{ ++$i }}</td>  
        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($stock_transfer->created_at)->format('d-m-Y h:i:s') }}</td> 
        <td data-title="{{ __('transfer_no') }}">{{ @$stock_transfer->transfer_no }}</td> 
        <td data-title="{{ __('from_branch') }}">{{ @$stock_transfer->fromBranch->name }}</td> 
        <td data-title="{{ __('to_branch') }}"  > {{ @$stock_transfer->toBranch->name }} </td>     
        <td data-title="{{ __('total_amount') }}">  {{ @businessCurrency($purchase->business_id) }} {{ @$stock_transfer->total_amount }} </td>   
        <td data-title="{{ __('status') }}">{!! @$stock_transfer->my_status !!}</td>   
        <td data-title="{{ __('transfered_by') }}">{{ @$stock_transfer->user->name }}</td>   
        <td data-title="{{ __('status_update') }}">
            @if($stock_transfer->status == \Modules\StockTransfer\Enums\StockTransferStatus::COMPLETED)
            <i class="fa-solid fa-ellipsis m-1"></i>
            @else
                <div class="dropdown ">
                    <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cogs"></i> 
                    </a>
                    <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> 
                        {!! @$stock_transfer->my_status_update !!}
                    </div>
                </div>
            @endif
        </td>   
        @if(hasPermission('stock_transfer_update') || hasPermission('stock_transfer_delete'))
            <td data-title="Action">
                @if($stock_transfer->status == \Modules\StockTransfer\Enums\StockTransferStatus::COMPLETED)
                <i class="fa fa-ellipsis"></i> 
                @else 
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cogs"></i>
                        </a>
                        <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> 
                            <a href="#" class="dropdown-item modalBtn" data-url="{{ route('stock.transfer.details',$stock_transfer->id) }}" data-title="{{ __('stock_transfer_details') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"  ><i class="fa fa-eye"></i>{{ __('view') }}</a> 
                            @if(hasPermission('stock_transfer_update'))
                                <a href="{{ route('stock.transfer.edit',@$stock_transfer->id) }}" class="dropdown-item "   ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                            @endif
                            @if(hasPermission('stock_transfer_delete'))
                                <form action="{{ route('stock.transfer.delete',@$stock_transfer->id) }}" method="post" class="delete-form" id="delete"  data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_stock_transfer') }}">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="dropdown-item "  >
                                        <i class="fas fa-trash-alt"></i>{{ __('delete') }}
                                    </button>
                                </form>
                            @endif  
                        </div>
                    </div> 
                @endif
            </td>
        @endif 
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="13" class="text-center">{{ __('stock_transfer_was_not_found') }}</td>
    </tr>
@endif
 
