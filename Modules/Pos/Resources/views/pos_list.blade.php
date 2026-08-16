@php
    $i=0;
@endphp 
    @foreach ($poses as $pos)                                        
    <tr> 
        <td data-title="#">{{ ++$i }}</td>  
        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($pos->created_at)->format('d-m-Y h:i:s') }}</td> 
        <td data-title="{{ __('invoice_no') }}">{{ @$pos->invoice_no }}</td> 
        @if(!isUser())
        <td data-title="{{ __('branch') }}"> {{ @$pos->branch->name }} </td> 
        @endif
        <td data-title="{{ __('customer') }}" class="purchase_supplier">
            <div class="d-flex"> 
                <span>{{ __('type') }}</span>    : {{ __(\Config::get('pos_default.customer_type.'.@$pos->customer_type))}}
            </div>
            @if($pos->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
            <div class="d-flex"> 
                <span>{{ __('name') }}</span>    : {{ @$pos->customer->name }}
            </div>
            <div class="d-flex">
                <span>{{ __('email') }}</span> : {{ @$pos->customer->email }}
            </div>
            <div class="d-flex">
                <span>{{ __('phone') }}</span>  : {{ @$pos->customer->phone }}
            </div> 
            <div class="d-flex">
                <span>{{ __('address') }}</span>  : <span class="address"> {{ @$pos->customer->address }}</span>
            </div> 
           
            @endif
        </td> 
        <td data-title="{{ __('payment_status') }}">
            {!! @$pos->my_payment_status !!}
        </td>   
        <td data-title="{{ __('shipping_status') }}">
              {{ __(\Config::get('pos_default.shpping_status.'.$pos->shipping_status)) }}
        </td>   
        <td data-title="{{ __('total_sell_price') }}">
            {{ @businessCurrency($pos->business_id) }} {{ @number_format($pos->total_sale_price,2) }}
        </td>   
        <td data-title="{{ __('created_by') }}">{{ @$pos->user->name }}</td>  
        <td data-title="Action">  
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs"></i>
                </a>
                <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a   class="dropdown-item "  href="{{ route('pos.print',$pos->id) }}" target="_blank"><i class="fa fa-print"></i>{{ __('print') }}</a>  
                    <a   class="dropdown-item "  href="{{ route('pos.invoice.view',$pos->id) }}"  ><i class="fa fa-file-invoice-dollar"></i>{{ __('invoice') }}</a>  
                    <a href="#" class="dropdown-item modalBtn" data-url="{{ route('pos.details',$pos->id) }}" data-title="{{ __('pos_details') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl"   ><i class="fa fa-eye"></i>{{ __('view') }}</a> 
                    @if(hasPermission('pos_read_payment'))
                        <a href="#" data-title="{{ __('manage_pos_payment') }}" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="{{ route('pos.manage.payment',$pos->id) }}"><i class="fa fa-hand-holding-dollar"></i>{{ __('payment') }}</a>  
                    @endif
                    
                    @if(hasPermission('pos_update'))
                        <a href="{{ route('pos.edit',@$pos->id) }}" class="dropdown-item "  ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                    @endif
                    @if(hasPermission('pos_delete'))
                        <form action="{{ route('pos.delete',@$pos->id) }}" method="post" class="delete-form" id="delete"  data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_pos') }}">
                            @method('delete')
                            @csrf
                            <button type="submit" class="dropdown-item"   >
                                <i class="fas fa-trash-alt"></i>{{ __('delete') }}
                            </button>
                        </form>
                    @endif  
                </div>
            </div>
        </td> 
    </tr>
    @endforeach
 