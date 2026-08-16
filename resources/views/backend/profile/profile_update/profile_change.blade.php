
<div class="tab-pane fade
@if(old('profile_name'))
active show
@elseif(!old('account_change') && !old('avatar_change') && !old('change_password') && !$request->change_password )
active show
@endif
" id="profile">
    <form class="row gy-3" action="{{ route('profile.update',['profile_name'=>'profile']) }}" method="post">
        @csrf
        <div class="col-md-12 ">

            <div class="row gy-3">

                <div class="col-md-4">
                    <label class="form-label" for="name">{{ __('name') }} <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="text" class="form-control form--control" id="name"
                        name="name" value="{{ old('name',Auth::user()->name) }}" >
                        @error('name')
                            <p class="mt-2 text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-12 text-end">
            <button type="submit" class="btn submit-btn btn-primary btn-sm">{{__('save_changes')}}</button>
        </div>
    </form>
</div>
