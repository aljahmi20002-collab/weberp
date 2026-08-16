@include('backend.partials.header')

    <div class="login-toggle-mode">
        <div class="toggle-mode">
            <i class="fas fa-sun"></i>
        </div>
    </div>
    <div class="main-loader">
        <div class="overlayer"></div>
        <div class="loader"></div>
    </div>
   <!-- Toggle Color Theme End -->
    @yield('maincontent')
@include('backend.partials.footer')
