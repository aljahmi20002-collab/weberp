
    <form action="{{ route('accounts.account.head.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3"> 

            <div class="col-lg-6 mt-3">
                <label for="type" class="form-label">{{ __('type') }} <span class="text-danger">*</span></label>
                <select class="form-control form--control select2" name="type">
                    <option selected disabled>{{ __('select') }} {{ __('type') }}</option>
                    @foreach (\Config::get('pos_default.statement_type') as $key=>$type )
                        <option value="{{ $key }}" @if(old('type') == $key) selected @endif>{{ __($type) }}</option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-6 mt-3">
                <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                @error('name')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-12 mt-3">
                <label for="note" class="form-label">{{ __('note') }} <span class="text-danger">*</span></label>
                <textarea class="form-control form--contrl" rows="5" name="note">{{ old('note') }}</textarea>
                @error('note')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-6 mt-4 ">
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
