<tr> 
    @if(isSuperadmin())
    <td data-title="{{ __('business') }}">{{ @$subcategory->business->business_name }}</td> 
    @endif
    <td data-title="{{ __('name') }}">-- {{ @$subcategory->name }}</td> 
    <td data-title="{{ __('image') }}"><img src="{{ @$category->image }}" width="50px"/></td> 
    <td data-title="{{ __('description') }}">{!!  $subcategory->description !!}</td>  
    <td data-title="{{ __('status') }}">
        {!! @$subcategory->my_status !!}  
    </td>

   

    <td data-title="{{ __('position') }}"> {{ @$subcategory->position  }} </td>  
    @if(hasPermission('category_update') || hasPermission('category_delete') || hasPermission('category_status_update'))
    <td data-title="Action"> 

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
            </a>
            <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">
                               
                @if(hasPermission('category_status_update'))
                    <a class="dropdown-item" href="{{ route('category.status.update',$category->id) }}">
                    {!!  $subcategory->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>'  !!} {{ @statusUpdate($subcategory->status) }}
                    </a>
                @endif 
                
                @if(hasPermission('category_update'))
                    <a href="{{ route('category.edit',@$subcategory->id) }}" class="dropdown-item"  ><i class="fas fa-pen"></i>{{ __('edit') }}</a>
                @endif
                @if(hasPermission('category_delete'))
                    <form action="{{ route('category.delete',@$subcategory->id) }}" method="post" class="delete-form" id="delete" data-yes={{ __('yes') }} data-cancel="{{ __('cancel') }}" data-title="{{ __('delete_category') }}">
                        @method('delete')
                        @csrf
                        <button type="submit" class="dropdown-item "  >
                            <i class="fas fa-trash-alt"></i>{{ __('delete') }}
                        </button>
                    </form>
                @endif 
            </div>
        </div>
    </td>
    @endif
</tr>