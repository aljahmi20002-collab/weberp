<div class="row">
    @if(hasPermission('attendance_delete'))
    <div class="col-12  text-right">
        <div class="dropdown text-right">
            <a href="#" class="dropdown-toggle text-primary" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             ...
            </a>
            <div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">
                <form action="{{ route('hrm.attendance.delete',@$attendance->id) }}"  method="post" >
                    @csrf
                    @method('delete')
                    <button type="submit" class="dropdown-item" >
                        {{ __('delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
<form action="{{ route('hrm.attendance.update',['id'=>$attendance->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="row d-flex text-left">
                <div class="col-2">
                    <img src="{{ @$attendance->user->image }}" width="60"/>
                </div>
                <div class="col-8">
                    <strong>{{@$attendance->user->name}}</strong>
                    <p>{{@$attendance->user->usertypes}}</p>
                </div>
             
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <span>Date : {{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</span>
        </div>

        <div class="col-lg-6  mt-3">
            <label for="check_in" class="form-label">{{ __('check_in') }} </label>
            <input type="time" name="check_in" class="form-control form--control" id="check_in" value="{{ old('check_in',@$attendance->check_in) }}" required>
            
        </div> 
        <div class="col-lg-6  mt-3">
            <label for="check_out" class="form-label">{{ __('check_out') }} </label>
            <input type="time" name="check_out" class="form-control form--control" id="check_out" value="{{ old('check_out',@$attendance->check_out) }}">
        </div> 

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save_changes')}}</button>
      </div>
</form>
  
