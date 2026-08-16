 
<form action="{{ route('settings.assetcategory.update',['id'=>$assetCategory->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row mt-3">  
        <div class="col-lg-6 mt-3">
            <label for="title" class="form-label">{{ __('title') }} <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form--control" id="title" value="{{ old('title',$assetCategory->title) }}">
            @error('title')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div> 
        <div class="col-lg-6 mt-3">
            <label for="position" class="form-label">{{ __('position') }} </label>
            <input type="text" name="position" class="form-control form--control" id="position" value="{{ old('position',$assetCategory->position) }}">
            @error('position')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-lg-6 mt-4 pt-lg-3">
            <div class="d-flex mt-3">
                <label class="form-label cmr-10">{{ __('status') }}</label>
                <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',$assetCategory->status) == App\Enums\Status::INACTIVE? '':'checked' }} >
                <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
            </div>
        </div> 

        <div class="col-md-12 mt-5 text-end">
            <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
        </div> 
    </div>
</form> 
    