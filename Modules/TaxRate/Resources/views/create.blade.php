 
<form action="{{ route('settings.tax.rate.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row mt-3"> 
        @if(isSuperadmin())
        <div class="col-lg-6 mt-3">
            <label for="business_id" class="form-label">{{ __('business') }} <span class="text-danger">*</span></label>
                <select class="form-control form--control select2" name="business_id">
                <option disabled selected>{{ __('select') }} {{ __('business') }}</option>
                @foreach ($businesses as $business)
                    <option value="{{ $business->id }}" @if(old('business_id') == $business->id) selected @endif>{{ $business->business_name }}</option> 
                @endforeach
                </select>
            @error('business_id')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        @endif
        <div class="col-lg-6 mt-3">
            <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
            @error('name')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-lg-6 mt-3">
            <label for="tax_rate" class="form-label">{{ __('tax_rate_parcentage') }} <span class="text-danger">*</span></label>
            <input type="text" name="tax_rate" class="form-control form--control" id="tax_rate" value="{{ old('tax_rate') }}">
            @error('tax_rate')
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

        <div class="col-lg-6 mt-4 pt-lg-3">
            <div class="d-flex mt-3">
                <label class="form-label cmr-10">{{ __('status') }}</label>
                <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
            </div>
        </div>

        <div class="col-md-12 mt-5 text-end">
            <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save')}}</button>
        </div>
    </div>
</form> 
