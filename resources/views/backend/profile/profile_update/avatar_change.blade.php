
    <div class="tab-pane text-center fade  {{ old('avatar_change') ? 'show active' :'' }} " id="avatar">
        <form action="{{ route('profile.update.avatar',['avatar_change'=>'avatar']) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="pupload mb-5">
                <div class="thumb">
                    <img class="pu-img" src="{{ Auth::user()->image }}" alt="clients">
                </div>
                <div class="remove-thumb">
                    <i class="fas fa-times"></i>
                </div>
                <div class="content">
                    <div class="mt-2">
                        <label class="btn btn-sm btn-primary">
                            <i class="fas fa-camera"></i> {{ __('change') }}  {{ __('avatar') }}
                            <input type="file" id="profile-image-upload" hidden="" name="avatar">
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn submit-btn btn-primary btn-sm">{{__('save_changes')}}</button>
            </div>
        </form>

    </div>
