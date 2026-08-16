@php
    $i=0;
@endphp
@if(count($purchases) >0)
    @foreach ($purchases as $purchase)                                        
    <tr> 
        <td data-title="#">{{ ++$i }}</td> 
         
        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y h:i:s') }}</td> 
        <td data-title="{{ __('purchase_no') }}">{{ @$purchase->purchase_no }}</td> 
        <td data-title="{{ __('branch') }}">
            @foreach ($purchase->PurchasedBranch as $branch)
                <span class="badge badge-pill  badge-primary">{{ @$branch->name }}</span>
            @endforeach 
        </td> 
        <td data-title="{{ __('supplier') }}" class="purchase_supplier">
            <div class="d-flex">
                <span>{{ __('name') }}</span>    : {{ @$purchase->supplier->name }}
            </div>
            <div class="d-flex">
                <span>{{ __('company') }}</span> : {{ @$purchase->supplier->company_name }}
            </div>
            <div class="d-flex">
                <span>{{ __('phone') }}</span>  : {{ @$purchase->supplier->phone }}
            </div> 
            <div class="d-flex">
                <span>{{ __('address') }}</span>  : <span class="address"> {{ @$purchase->supplier->address }}</span>
            </div> 
        </td>   
        <td data-title="{{ __('purchase_status') }}">{!! @$purchase->my_purchase_status !!}</td>   
        <td data-title="{{ __('payment_status') }}">{!! @$purchase->my_payment_status !!}</td>   
        <td data-title="{{ __('total_purchase_cost') }}">
            {{ @businessCurrency($purchase->business_id) }} {{ @number_format($purchase->total_purchase_cost,2) }}
        </td>   
        <td data-title="{{ __('received_by') }}">{{ @$purchase->user->name }}</td>    
            <td data-title="Action">  
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cogs"></i>
                    </a>
                    <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">                       
                        <a href="{{ route('purchase.invoice.view',$purchase->id) }}"  class="dropdown-item" ><i class="fa fa-print"></i>{{ __('print') }}</a>  
                        <a href="#" class="dropdown-item modalBtn" data-url="{{ route('purchase.details',$purchase->id) }}" data-title="{{ __('purchase_details') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"   ><i class="fa fa-eye"></i>{{ __('view') }}</a> 
                        @if(hasPermission('purchase_read_payment'))
                        <a href="#" data-title="{{ __('manage_payment') }}" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="{{ route('purchase.manage.payment',$purchase->id) }}"> <i class="fa fa-hand-holding-dollar"></i>{{ __('payment') }}</a>  
                        @endif 
                        @if(hasPermission('purchase_update'))
                            <a href="{{ route('purchase.edit',@$purchase->id) }}" class="dropdown-item"  ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                        @endif
                        @if(hasPermission('purchase_delete'))
                            <form action="{{ route('purchase.delete',@$purchase->id) }}" method="post" class="delete-form" id="delete"  data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_purchase') }}">
                                @method('delete')
                                @csrf
                                <button type="submit" class="dropdown-item " >
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
        <td colspan="13" class="text-center">{{ __('purchase_was_not_found') }}</td>
    </tr>
@endif
 
