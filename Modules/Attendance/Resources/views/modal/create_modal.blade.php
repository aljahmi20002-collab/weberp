<form action="{{ route('hrm.attendance.store',['employee_id'=>$request->employee_id,'date'=>$request->date]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="row d-flex text-left">
                <div class="col-2">
                    <img src="{{ @$user->image }}" width="60"/>
                </div>
                <div class="col-10">
                    <strong>{{@$user->name}}</strong>
                    <p>{{@$user->usertypes}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <span>Date : {{ \Carbon\Carbon::parse($request->date)->format('d-m-Y') }}</span>
        </div>

        <div class="col-lg-6  mt-3">
            <label for="check_in" class="form-label">{{ __('check_in') }} </label>
            <input type="time" name="check_in" class="form-control form--control" id="check_in" value="{{ old('check_in',date('H:i')) }}">
            @error('check_in')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div> 
        <div class="col-lg-6  mt-3">
            <label for="check_out" class="form-label">{{ __('check_out') }} </label>
            <input type="time" name="check_out" class="form-control form--control" id="check_out" value="{{ old('check_out') }}">
            @error('check_out')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div> 

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save')}}</button>
      </div>
</form>
