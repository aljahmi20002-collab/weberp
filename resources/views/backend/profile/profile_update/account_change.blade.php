
<div class="tab-pane fade {{ old('account_change') ? 'show active' :'' }} " id="account">
    <form class="row gy-3" action="{{ route('profile.account.update',['account_change' =>'account']) }}" method="post">
        @csrf
        <div class="col-md-12 ">
            <div class="row gy-3">
                <div class="col-md-12">
                    <label class="form-label" for="phone">{{ __('phone') }}</label>
                    <div class="position-relative">
                        <input type="text" class="form-control form--control" id="phone"
                        name="phone" value="{{ old('phone',Auth::user()->phone) }}" >
                        @error('phone')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
 
                <div class="col-md-6">
                    <label class="form-label" for="address">{{ __('address') }}</label>
                    <div class="position-relative">
                        <textarea name="address" id="address" rows="5" class="form--control" >{{ Auth::user()->address }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="about">{{ __('about') }}</label>
                    <div class="position-relative">
                        <textarea name="about" id="about" rows="5" class=" form--control" >{{ Auth::user()->about }}</textarea>
                    </div>
                </div>
 
            </div>
        </div>

        <div class="col-md-12 text-end">
            <button type="submit" class="btn submit-btn btn-primary btn-sm">{{__('save_changes')}}</button>
        </div>
    </form>
</div>


