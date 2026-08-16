
<form action="{{ route('units.store') }}" method="post" enctype="multipart/form-data"  >
    @csrf
    <div class="row"> 
        @if(isSuperadmin())
            <div class="col-6 mt-3">
                <label for="business" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                <select class=" form-control form--control select2" name="business_id" required id="business_id"  >
                    <option  disabled selected>{{ __('select') }} {{ __('business') }}</option> 
                        @foreach ($businesses as $business )
                            <option  value="{{ $business->id }}" @if(old('business_id') == $business->id) selected @endif>{{  @$business->business_name }}</option>
                        @endforeach 
                </select>
                @error('business_id')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>
        @endif  

        <div class="col-lg-6 mt-3">
            <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form--control" required id="name" value="{{ old('name') }}">
            @error('name')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>   
        <div class="col-lg-6 mt-3">
            <label for="short_name" class="form-label">{{ __('short_name') }}<span class="text-danger">*</span></label>
            <input type="text" name="short_name" class="form-control form--control" required id="short_name" value="{{ old('short_name') }}">
            @error('short_name')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>  
  
        <div class="col-lg-6 mt-3">
            <label for="position" class="form-label">{{ __('position') }} </label>
            <input type="text" name="position" class="form-control form--control" id="position" value="{{ old('position') }}">
            @error('position')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-lg-6  mt-4 pt-lg-3">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex mt-3">
                        <label class="form-label cmr-10">{{ __('status') }}</label>
                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                    </div>
                </div> 
            </div>
        </div> 
              
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
        </div>
    </div>
</form>  
<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>  
<script src="{{static_asset('backend/js')}}/select2/select2.min.js"></script> 
<script src="{{static_asset('backend/js')}}/select2/modal-select2.js"></script>  
 