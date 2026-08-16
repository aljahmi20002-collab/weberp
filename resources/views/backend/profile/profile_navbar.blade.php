  <!-- User Profile Top Ends -->
  <div class="dashboard--widget p-0">
    <ul class="nav nav-pills ">
        <li class="nav-item">
            <a class="nav-link
            @if(old('profile_name'))
            active
            @elseif(!old('account_change') && !old('avatar_change') && !old('change_password') && !$request->change_password)
            active
            @endif"
             data-bs-toggle="tab" href="#profile">{{ __('profile') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ old('account_change') ? 'active' :'' }} "   data-bs-toggle="tab" href="#account">{{ __('account') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ old('avatar_change') ? 'active' :'' }}"   data-bs-toggle="tab" href="#avatar" >{{ __('avatar') }}</a>
        </li>
        @if(!Auth::user()->google_id && !Auth::user()->facebook_id)
        <li class="nav-item">
            <a class="nav-link @if(old('change_password') || $request->change_password) active @endif "   data-bs-toggle="tab" href="#change_password">{{ __('change_password') }}</a>
        </li>
        @endif
    </ul>

</div>
