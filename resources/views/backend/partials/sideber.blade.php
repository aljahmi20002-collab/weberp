<aside class="user-panel-sidebar">
    <div class="sidebar-close d-xl-none">
        &times;
    </div>
    <!-- Sidebar Author Area -->
    <div class="author border-bottom">
        <a href="{{ url('/') }}" class="d-block text-white text-left">
            <img src="{{ businessLogo() }}" class="m-0" alt="images"><br>
            @if (isUser())
                {{ Auth::user()->branch->name }}
            @endif
        </a>
    </div>
    <!-- Sidebar Menu Area -->
    <ul class="nav-menu pt-3">
        <li class="{{ request()->is('/') ? 'open' : '' }}">
            <a href="{{ route('dashboard.index') }}">
                <i class="fas fa-home"></i>
                <span>{{ __('dashboard') }}</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-original-title="Dashboard">
                <i class="fas fa-home"></i>
            </a>
        </li>

        @if (!isSuperadmin())

            {{-- user --}}
            @if (hasPermission('user_read'))
                <li class="{{ request()->is('user*') ? 'open' : '' }}">
                    <a href="{{ route('user.index') }}">
                        <i class="fa fa-users"></i>
                        <span>{{ __('users') }}</span>
                    </a>
                    <a href="{{ route('user.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="users">
                        <i class="fa fa-users"></i>
                    </a>
                </li>
            @endif
            {{-- end user --}}
        @else
            @if (hasPermission('role_read') || hasPermission('user_read'))
                <li class="{{ request()->is('role*', 'user*') ? 'open' : '' }}">
                    <a href="#0">
                        <i class="fa fa-users"></i>
                        <span>{{ __('role') }} & {{ __('user') }}</span>
                    </a>
                    <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-original-title="Pages">
                        <i class="far fa-file-alt"></i>
                    </a>
                    <ul @if (request()->is('role*', 'user*')) style="display:block" @endif>
                        {{-- roles --}}
                        @if (hasPermission('role_read'))
                            <li class="{{ request()->is('role*') ? 'open' : '' }}">
                                <a href="{{ route('role.index') }}">
                                    <i class="fas fa-angles-right"></i>
                                    <span>{{ __('role') }}</span>
                                </a>
                                <a href="{{ route('role.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-original-title="Role">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        @endif
                        {{-- end roles --}}

                        {{-- user --}}
                        @if (hasPermission('user_read'))
                            <li class="{{ request()->is('user*') ? 'open' : '' }}">
                                <a href="{{ route('user.index') }}">
                                    <i class="fas fa-angles-right"></i>
                                    <span>{{ __('user') }}</span>
                                </a>
                                <a href="{{ route('user.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-original-title="User">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        @endif
                        {{-- end user --}}
                    </ul>
                </li>
            @endif
        @endif


        {{-- Department --}}
        @if (isSuperadmin())
            @if (hasPermission('department_read'))
                <li class="{{ request()->is('hrm/department*') ? 'open' : '' }}">
                    <a href="{{ route('hrm.department.index') }}">
                        <i class="fas fa-list"></i>
                        <span>{{ __('department') }}</span>
                    </a>
                    <a href="{{ route('hrm.department.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-original-title="department">
                        <i class="fas fa-list"></i>
                    </a>
                </li>
            @endif
        @endif
        {{-- end Department --}}



        @if (!isSuperadmin())
            @include('backend.partials.business_sidebar')
        @endif

        {{-- admin support --}}
        @if (isSuperadmin() || business())
            @if (hasPermission('supports_read'))
                <li class="{{ request()->is('ticket*') ? 'open' : '' }}">
                    <a href="{{ route('ticket.index') }}">
                        @if (isSuperadmin())
                            <i class="fa-solid fa-paper-plane"></i>
                            <span> {{ __('support') }} </span>
                        @else
                            <i class="fa-solid fa-headset"></i>
                            <span>{{ __('need_help') }}</span>
                        @endif
                    </a>
                    <a href="{{ route('ticket.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-original-title=" @if (isSuperadmin()) {{ __('support') }}
                            @else
                                {{ __('need_help') }} @endif">
                        @if (isSuperadmin())
                            <i class="fa-solid fa-paper-plane"></i>
                        @else
                            <i class="fa-solid fa-headset"></i>
                        @endif
                    </a>
                </li>
            @endif
        @endif



        {{-- business --}}
        @if (hasPermission('business_read'))
            <li class="{{ request()->is('business*') ? 'open' : '' }}">
                <a href="{{ route('business.index') }}">
                    <i class="fas fa-list-alt"></i>
                    <span>{{ __('business') }}</span>
                </a>
                <a href="{{ route('business.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                    data-bs-placement="right" data-bs-original-title="{{ __('business') }}">
                    <i class="fas fa-list-alt"></i>
                </a>
            </li>
        @endif
        {{-- Activity logs --}}
        @if (hasPermission('activity_logs_read'))
            <li class="{{ request()->is('activity-logs*') ? 'open' : '' }}">
                <a href="{{ route('activity.logs.index') }}">
                    <i class="fas fa-history"></i>
                    <span>{{ __('activity_logs') }}</span>
                </a>
                <a href="{{ route('activity.logs.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                    data-bs-placement="right" data-bs-original-title="{{ __('activity_logs') }}">
                    <i class="fas fa-history"></i>
                </a>
            </li>
        @endif
        {{-- login activity logs --}}
        @if (hasPermission('login_activity_read'))
            <li class="{{ request()->is('login-activity*') ? 'open' : '' }}">
                <a href="{{ route('login.activity.index') }}">
                    <i class="fas fa-history"></i>
                    <span>{{ __('login_activity') }}</span>
                </a>
                <a href="{{ route('login.activity.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                    data-bs-placement="right" data-bs-original-title="{{ __('login_activity') }}">
                    <i class="fas fa-history"></i>
                </a>
            </li>
        @endif

        @if (hasPermission('plans_read'))
            <li class="{{ request()->is('plan*', 'subscription*') ? 'open' : '' }}">
                <a href="#0">
                    <i class="fa fa-users"></i>
                    <span>{{ __('plan') }} & {{ __('subscription') }}</span>
                </a>
                <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                    data-bs-original-title="{{ __('plan') }} & {{ __('subscription') }}">
                    <i class="fa fa-users"></i>
                </a>
                <ul @if (request()->is('plans*', 'subscription*')) style="display:block" @endif>
                    {{-- Plans --}}
                    @if (hasPermission('plans_read'))
                        <li class="{{ request()->is('plans*') ? 'open' : '' }}">
                            <a href="{{ route('plans.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('plans') }}</span>
                            </a>
                            <a href="{{ route('plans.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                                data-bs-placement="right" data-bs-original-title="{{ __('plans') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif

                    @if (hasPermission('subscription_read'))
                        <li class="{{ request()->is('subscription*') ? 'open' : '' }}">
                            <a href="{{ route('subscription.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('subscription') }}</span>
                            </a>
                            <a href="{{ route('subscription.index') }}" class="collapsed-icon"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-original-title="{{ __('subscriptions') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif
                    {{-- end Plans --}}
                </ul>
            </li>
        @endif



        {{-- backup --}}
        @if (hasPermission('backup_read'))
            <li class="{{ request()->is('backup*') ? 'open' : '' }}">
                <a href="{{ route('backup.index') }}">
                    <i class="fas fa-database"></i>
                    <span>{{ __('backup') }}</span>
                </a>
                <a href="{{ route('backup.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                    data-bs-placement="right" data-bs-original-title="{{ __('backup') }}">
                    <i class="fas fa-database"></i>
                </a>
            </li>
        @endif


        {{-- languages --}}
        @if (hasPermission('language_read'))
            <li class="{{ request()->is('language*') ? 'open' : '' }}">
                <a href="{{ route('language.index') }}">
                    <i class="fas fa-language"></i>
                    <span>{{ __('languages') }}</span>
                </a>
                <a href="{{ route('language.index') }}" class="collapsed-icon" data-bs-toggle="tooltip"
                    data-bs-placement="right" data-bs-original-title="Language">
                    <i class="fas fa-language"></i>
                </a>
            </li>
        @endif

        @if (hasPermission('settings_read') ||
                hasPermission('general_settings_read') ||
                hasPermission('mail_settings_read') ||
                hasPermission('currency_read') ||
                hasPermission('branch_read') ||
                hasPermission('tax_rate_read'))
            <li class="{{ request()->is('settings*', 'head-of-account*') ? 'open' : '' }}">
                <a href="#0">
                    <i class="fa fa-cogs"></i>
                    <span>{{ __('settings') }}</span>
                </a>
                <a href="#0" class="collapsed-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                    data-bs-original-title="Pages">
                    <i class="far fa-file-alt"></i>
                </a>
                <ul @if (request()->is('settings*', 'head-of-account*')) style="display:block" @endif>
                    {{-- general settings --}}
                    @if (hasPermission('general_settings_read'))
                        <li class="{{ request()->is('settings/general-settings*') ? 'open' : '' }}">
                            <a href="{{ route('settings.general.settings.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('general_settings') }}</span>
                            </a>
                            <a href="{{ route('settings.general.settings.index') }}" class="collapsed-icon"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-original-title="{{ __('general_settings') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif

                    {{-- mail settings --}}
                    @if (hasPermission('mail_settings_read'))
                        <li class="{{ request()->is('settings/mail-settings*') ? 'open' : '' }}">
                            <a href="{{ route('settings.mail.settings.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('mail_settings') }}</span>
                            </a>
                            <a href="{{ route('settings.mail.settings.index') }}" class="collapsed-icon"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-original-title="{{ __('mail_settings') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif


                    {{-- payment settings --}}
                    @if (hasPermission('payment_settings_read'))
                        <li class="{{ request()->is('settings/payment-settings*') ? 'open' : '' }}">
                            <a href="{{ route('settings.payment.settings.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('payment_settings') }}</span>
                            </a>
                            <a href="{{ route('settings.payment.settings.index') }}" class="collapsed-icon"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-original-title="{{ __('payment_settings') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif

                    @if (hasPermission('currency_read'))
                        <li class="{{ request()->is('settings/currency*') ? 'open' : '' }}">
                            <a href="{{ route('settings.currency.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('currency') }}</span>
                            </a>
                            <a href="{{ route('settings.currency.index') }}" class="collapsed-icon"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-original-title="{{ __('currency') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif

                    {{-- business settings modules --}}
                    @if (business() == true)
                        <li class="{{ request()->is('settings/business-settings*') ? 'open' : '' }}">
                            <a href="{{ route('settings.business.settings.index') }}">
                                <i class="fas fa-angles-right"></i>
                                <span>{{ __('business_settings') }}</span>
                            </a>
                            <a href="{{ route('settings.business.settings.index') }}" class="collapsed-icon"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-original-title="{{ __('business_settings') }}">
                                <i class="fas fa-angles-right"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</aside>
