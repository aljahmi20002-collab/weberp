
    <div class="tab-pane fade @if(old('change_password') || $request->change_password) active show @endif" id="change_password">

        <form class="row gy-3" action="{{ route('update.password',['change_password'=>'password']) }}" method="post">
            @csrf
            <div class="col-md-12 ">

                <div class="row gy-3">
                    <div class="col-md-4">
                        <label class="form-label" for="password">{{ __('current_password') }}</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form--control" id="password"
                            placeholder="********" name="current_password" value="{{ old('current_password') }}" >

                            @error('current_password')
                                <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="new-password">{{ __('new_password') }}</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form--control" id="new-password"
                            placeholder="********" name="new_password" value={{ old('new_password') }}>

                            @error('new_password')
                                <p class="mt-2 text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="confirm-password">{{ __('confirm_password') }}</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form--control"
                                id="confirm-password" placeholder="********" name="confirm_password" value={{ old('confirm_password') }} >

                                @error('confirm_password')
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
