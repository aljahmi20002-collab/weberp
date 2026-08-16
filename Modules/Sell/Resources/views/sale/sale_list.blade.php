@php
    $i=0;
@endphp
@if(count($sales) >0)
    @foreach ($sales as $sale)                                        
    <tr> 
        <td data-title="#">{{ ++$i }}</td> 
      
        <td data-title="{{ __('date') }}">{{ \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y h:i:s') }}</td> 
        <td data-title="{{ __('invoice_no') }}">{{ @$sale->invoice_no }}</td> 
        @if(!isUser())
        <td data-title="{{ __('branch') }}"> {{ @$sale->branch->name }} </td> 
        @endif
        <td data-title="{{ __('customer') }}" class="purchase_supplier">
            <div class="d-flex"> 
                <span>{{ __('type') }}</span>    : {{ __(\Config::get('pos_default.customer_type.'.@$sale->customer_type))}}
            </div>
            @if($sale->customer_type == \Modules\Customer\Enums\CustomerType::EXISTING_CUSTOMER)
            <div class="d-flex"> 
                <span>{{ __('name') }}</span>    : {{ @$sale->customer->name }}
            </div>
            <div class="d-flex">
                <span>{{ __('email') }}</span> : {{ @$sale->customer->email }}
            </div>
            <div class="d-flex">
                <span>{{ __('phone') }}</span>  : {{ @$sale->customer->phone }}
            </div> 
            <div class="d-flex">
                <span>{{ __('address') }}</span>  : <span class="address"> {{ @$sale->customer->address }}</span>
            </div> 
            @endif
        </td> 
        <td data-title="{{ __('payment_status') }}">
            {!! @$sale->my_payment_status !!}
        </td>   
        <td data-title="{{ __('shipping_status') }}">
              {{ __(\Config::get('pos_default.shpping_status.'.$sale->shipping_status)) }}
        </td>   
        <td data-title="{{ __('total_sell_price') }}">
            {{ @businessCurrency($sale->business_id) }} {{ @number_format($sale->total_sale_price,2) }}
        </td>   
        <td data-title="{{ __('returned_by') }}">{{ @$sale->user->name }}</td>  
        <td data-title="Action">   
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs"></i>
                </a>
                <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton"> 
                    <a href="{{ route('sale.print',$sale->id) }}" data-title="{{ __('print') }}" class="dropdown-item" target="_blank" ><i class="fa fa-print"></i>{{ __('print') }}</a>  
                    <a href="{{ route('sale.invoice.view',$sale->id) }}" class="dropdown-item"  ><i class="fa fa-file-invoice-dollar"></i>{{ __('invoice') }}</a>  
                    <a href="#" class="dropdown-item modalBtn" data-url="{{ route('sale.details',$sale->id) }}" data-title="{{ __('sale_details') }}" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-xl" ><i class="fa fa-eye"></i> {{ __('view') }}</a> 
                    @if(hasPermission('sale_read_payment') )
                        <a href="#" data-title="{{ __('manage_sale_payment') }}" class="dropdown-item modalBtn" data-modalsize="modal-xl" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-url="{{ route('sale.manage.payment',$sale->id) }}"><i class="fa fa-hand-holding-dollar"></i>{{ __('payment') }}</a>  
                    @endif
                    
                    @if(hasPermission('sale_update'))
                        <a href="{{ route('sale.edit',@$sale->id) }}" class="dropdown-item" ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                    @endif
                    @if(hasPermission('sale_delete'))
                        <form action="{{ route('sale.delete',@$sale->id) }}" method="post" class="delete-form" id="delete"  data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_sale') }}">
                            @method('delete')
                            @csrf
                            <button type="submit" class="dropdown-item">
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
        <td colspan="13" class="text-center">{{ __('sale_was_not_found') }}</td>
    </tr>
@endif
 
