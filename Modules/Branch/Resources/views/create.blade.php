 
<form action="{{ route('settings.branch.store',['business_id'=>Auth::user()->business->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row mt-3">
        <div class="col-lg-6 mt-3">
            <label for="branch_name" class="form-label">{{ __('branch_name') }} <span class="text-danger">*</span></label>
            <input type="text" name="branch_name" class="form-control form--control" id="branch_name" value="{{ old('branch_name') }}">
            @error('branch_name')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-lg-6 mt-3">
            <label for="email" class="form-label">{{ __('email') }} <span class="text-danger">*</span></label>
            <input type="text" name="email" class="form-control form--control" id="email" value="{{ old('email') }}">
            @error('email')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>


        <div class="col-sm-6 mt-2">
            <label for="website" class="form-label">{{ __('website') }} <span class="text-danger">*</span></label>
            <input type="text" name="website" class="form-control form--control" id="website" value="{{ old('website') }}">
            @error('website')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-sm-6 mt-2">
            <label for="business_phone" class="form-label">{{ __('phone') }} <span class="text-danger">*</span></label>
            <input type="text" name="business_phone" class="form-control form--control" id="business_phone" value="{{ old('business_phone') }}">
            @error('business_phone')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-sm-6 mt-2 branch-country">
            <label for="country" class="form-label">{{ __('country') }} <span class="text-danger">*</span></label>
            <select class="form-control form--control select2" name="country">
                <option selected disabled>{{ __('select') }} {{ __('country') }}</option>
                @foreach (currency() as $currency)
                    <option value="{{ $currency->id }}" @if(old('country') == $currency->id) selected @endif>{{ $currency->country }}</option>
                @endforeach
            </select>
            @error('country')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-sm-6 mt-2">
            <label for="state" class="form-label">{{ __('state') }} <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control form--control" id="state" value="{{ old('state') }}">
            @error('state')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-md-6 mt-2 ">
            <label for="city" class="form-label">{{ __('city') }} <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control form--control" id="city" value="{{ old('city') }}">
            @error('city')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-md-6 mt-2">
                    <label for="zip_code" class="form-label">{{ __('zip_code') }} <span class="text-danger">*</span></label>
                    <input type="text" name="zip_code" class="form-control form--control" id="zip_code" value="{{ old('zip_code') }}">
                    @error('zip_code')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-lg-6 mt-4 pt-lg-3">
                    <div class="d-flex mt-2">
                        <label class="form-label cmr-10">{{ __('status') }}</label>
                        <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                        <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-5 text-end">
            <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('save')}}</button>
        </div>
    </div>
</form> 

{{-- <script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>   --}}
<script src="{{static_asset('backend/js')}}/select2/select2.min.js"></script> 
<script src="{{static_asset('backend/js')}}/select2/modal-select2.js"></script>  
 