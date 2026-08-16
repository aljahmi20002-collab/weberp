@php
    $i=0;
@endphp
@if(count($purchases_return) >0)
    @foreach ($purchases_return as $purchase_return)                                        
    <tr> 
        <td data-title="#">{{ ++$i }}</td> 
       
        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($purchase_return->created_at)->format('d-m-Y h:i:s') }}</td> 
        <td data-title="{{ __('purchase_no') }}">{{ @$purchase_return->return_no }}</td> 
        <td data-title="{{ __('branch') }}">
            @foreach ($purchase_return->PurchasedReturnBranch as $branch)
                <span class="badge badge-pill  badge-primary">{{ @$branch->name }}</span>
            @endforeach 
        </td> 
        <td data-title="{{ __('supplier') }}" class="purchase_supplier">
            <div class="d-flex">
                <span>{{ __('name') }}</span>    : {{ @$purchase_return->supplier->name }}
            </div>
            <div class="d-flex">
                <span>{{ __('company') }}</span> : {{ @$purchase_return->supplier->company_name }}
            </div>
            <div class="d-flex">
                <span>{{ __('phone') }}</span>  : {{ @$purchase_return->supplier->phone }}
            </div> 
            <div class="d-flex">
                <span>{{ __('phone') }}</span>  : <span class="address"> {{ @$purchase_return->supplier->address }}</span>
            </div> 
        </td>    
        <td data-title="{{ __('payment_status') }}">{!! @$purchase_return->my_payment_status !!}</td>   
        <td data-title="{{ __('total_purchase_price') }}">
            {{ @businessCurrency($purchase_return->business_id) }} {{ @number_format($purchase_return->total_purchase_return_price,2) }}
        </td>   
        <td data-title="{{ __('returned_by') }}">{{ @$purchase_return->user->name }}</td>   

        <td data-title="Action"> 
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs"></i>
                </a>
                <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> 
                    <a href="{{ route('purchase.return.invoice.view',$purchase_return->id) }}"   class="dropdown-item " ><i class="fa fa-print"></i>{{ __('print') }}</a>  
                    <a href="#" class="dropdown-item modalBtn" data-url="{{ route('purchase.return.details',$purchase_return->id) }}" data-title="{{ __('purchase_return_details') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"  ><i class="fa fa-eye"></i>{{ __('view') }}</a> 
                    @if(hasPermission('purchase_return_read_payment'))
                        <a href="#" data-title="{{ __('manage_return_payment') }}" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="{{ route('purchase.return.manage.payment',$purchase_return->id) }}"><i class="fa fa-hand-holding-dollar"></i>{{ __('payment') }}</a>  
                    @endif 
                    @if(hasPermission('purchase_return_update'))
                        <a href="{{ route('purchase.return.edit',@$purchase_return->id) }}" class="dropdown-item" ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                    @endif
                    @if(hasPermission('purchase_return_delete'))
                        <form action="{{ route('purchase.return.delete',@$purchase_return->id) }}" method="post" class="delete-form" id="delete"  data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_purchase_return') }}">
                            @method('delete')
                            @csrf
                            <button type="submit" class="dropdown-item"  >
                                <i class="fas fa-trash-alt"></i>{{ __('delete') }}
                            </button>
                        </form>
                    @endif 
                </div>
            </div> 
        </td> 
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="13" class="text-center">{{ __('purchase_return_was_not_found') }}</td>
    </tr>
@endif
 
