    <!-- Template Header Start -->
    <header>
        <div class="header-wrapper">
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="header-right-icons"> 
                <div>
                    <a href="{{ route('cache.clear') }}" class="btn btn-primary mx-3 text-capitalize">{{ __('cache_clear') }}</a>
                </div> 
                @if (!isSuperadmin())
                    <div>
                        <a href="{{ route('business.subscription.index') }}"
                            class="btn btn-success mx-3 text-capitalize">{{ __('subscribe') }}</a>
                    </div>
                @endif
                @if (isUser() && Auth::user()->branch)
                    <div>
                        <a class="btn btn-sm btn-primary mx-3">
                            {{ __('balance') }}:
                            {{ businessCurrency(business_id()) }}{{ Auth::user()->branch->balance }}
                        </a>
                    </div>
                @endif
                <div class="language">
                    <a href="#">
                        <i class="{{ languageicon(app()->getLocale()) }}"></i>
                    </a>
                    <ul>
                        @foreach (language() as $lang)
                            <li>
                                <a href="{{ route('localization', [$lang->code]) }}">
                                    <i class="{{ $lang->icon_class }}"></i>
                                    <span class="name">{{ $lang->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="user">
                    <a href="#0">
                        <img src="{{ Auth::user()->image }}" alt="language">
                        <span class="name">{{ Auth::user()->name }}</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('profile.index') }}">
                                <i class="far fa-user"></i>
                                <span>{{ __('profile') }}</span>
                            </a>
                        </li>
                        @if (!Auth::user()->google_id && !Auth::user()->facebook_id)
                            <li>
                                <a href="{{ route('profile.index', ['change_password' => 'password']) }}">
                                    <i class="fas fa-key"></i>
                                    <span>{{ __('change_password') }}</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fas fa-power-off"></i>
                                <span>{{ __('logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="post">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="toggle-mode">
                    <i class="fas fa-sun"></i>
                </div>
            </div>
        </div>
    </header>
    <!-- Template Header End -->
