@include('backend.partials.header')
    <!-- Template Header Start -->
    <header class="error-header">
        <div class="header-wrapper">
            <div class="header-right-icons">
                <div class="toggle-mode">
                    <i class="fas fa-sun"></i>
                </div>
            </div>
        </div>
    </header>
    <!-- Template Header End -->


    <div class="user-panel-wrapper">
        <div class="user-panel-content error-content">
            <div class="error-content ">
                <h2 class="title">Opps !!</h2>
                <h4 class="subtitle">@yield('code') - @yield('type')</h4>
                <p>@yield('message') </p>
                <a href="{{ url('/') }}" class="btn btn-primary">Go to homepage</a>
            </div>

        </div>
    </div>

@include('backend.partials.footer')
