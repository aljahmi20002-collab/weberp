<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width,  minimum-scale=0.8, maximum-scale = 0.8, user-scalable = no , shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ static_asset('img/brand/icon.svg') }}" type="image/svg+xml">
    <link rel="icon" type="image/png" href="{{ static_asset('img/brand/favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/installer/custom_two.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/installer/custom_one.css">
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/all.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/installer/progressbar.css">
    <link rel='stylesheet' type='text/css' href="{{ static_asset('backend/installer/styleone.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cairo:wght@400;600;700&display=swap">
    <title>WebERP / WebPOS — Business Management with POS (SaaS)</title>
    <style>
      body { font-family: 'Inter', 'Cairo', system-ui, -apple-system, sans-serif; background: linear-gradient(135deg, #EEF2FF 0%, #F5F3FF 100%); min-height: 100vh; }
      .installer-container { padding: 32px 0; }
      .installer { background: #fff; border-radius: 22px; box-shadow: 0 30px 80px -20px rgba(79,70,229,.25); overflow: hidden; max-width: 880px; margin: 0 auto; }
      .installer-header-box { background: #fff; }
      .panel-heading { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); padding: 36px 24px !important; border-radius: 0; }
      .panel-heading h2 { color: #fff; font-weight: 700; margin: 4px 0; }
      .panel-heading h2:first-child { font-size: 26px; letter-spacing: -0.02em; }
      .panel-heading h2:last-child  { font-size: 15px; font-weight: 500; opacity: 0.92; line-height: 1.6; }
      .panel-heading::before {
        content: ''; display: block; margin: 0 auto 18px; width: 72px; height: 72px;
        background: rgba(255,255,255,.15) url("{{ static_asset('img/brand/icon.svg') }}") center/44px no-repeat;
        border-radius: 16px;
      }
      .form-control { border-radius: 10px; border: 1px solid #E2E8F0; padding: 10px 14px; box-shadow: none !important; }
      .form-control:focus { border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important; }
      .btn-primary, button[type=submit] { background: linear-gradient(135deg, #4F46E5, #7C3AED); border: 0; padding: 10px 24px; border-radius: 10px; font-weight: 600; box-shadow: 0 10px 25px -8px rgba(79,70,229,.45); }
      .title { font-weight: 700; color: #0F172A; }
      .section p { color: #64748B; }
      .text-danger, #error_m { background: #FEF2F2; color: #991B1B; border-radius: 10px; padding: 12px 16px; }
      #progressbar li { background: #CBD5E1; }
      #progressbar li.active { background: linear-gradient(135deg, #4F46E5, #7C3AED); }
    </style>
</head>

<body>
    <div class="installer-container">
        <div class="container ">
            @if ($errors->any())
                <div id="alert-container" class="text-left mt-3">
                    @isset($errors)
                        @if ($errors->any())
                            <div class="alert alert-danger" id="error_m">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                @endif
            </div>
            @endif

            <?php
            $php_version_success = false;
            $mysql_success = false;
            $curl_success = false;
            $gd_success = false;
            $allow_url_fopen_success = false;
            $timezone_success = false;
            $php_version_required = '8.1';
            $current_php_version = PHP_VERSION;
            //check required php version
            if (version_compare($current_php_version, $php_version_required) >= 0) {
                $php_version_success = true;
            }
            //check mySql
            if (function_exists('mysqli_connect')) {
                $mysql_success = true;
            }
            //check curl
            if (function_exists('curl_version')) {
                $curl_success = true;
            }
            //check gd
            if (extension_loaded('gd') && function_exists('gd_info')) {
                $gd_success = true;
            }
            //check allow_url_fopen
            if (ini_get('allow_url_fopen')) {
                $allow_url_fopen_success = true;
            }
            //check allow_url_fopen
            $timezone_settings = ini_get('date.timezone');
            if ($timezone_settings) {
                $timezone_success = true;
            }
            //check if all requirement is success
            if ($php_version_success && $mysql_success && $curl_success && $gd_success && $allow_url_fopen_success && $timezone_success) {
                $all_requirement_success = true;
            } else {
                $all_requirement_success = false;
            }
            if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                $writeable_directories = ['../routes', '../resources', '../public', '../storage', '../.env'];
            } else {
                $writeable_directories = ['./routes', './resources', './public', './storage', '.env'];
            }
            foreach ($writeable_directories as $value) {
                if (!is_writeable($value)) {
                    $all_requirement_success = false;
                }
            }
            $dashboard_url = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
            $dashboard_url = preg_replace('/install.*/', '', $dashboard_url); //remove everything after index.php
            if (!empty($_SERVER['HTTPS'])) {
                $dashboard_url = 'https://' . $dashboard_url;
            } else {
                $dashboard_url = 'http://' . $dashboard_url;
            }
            ?>

            <div class="row">
                <div class="col-12 m-auto">
                    <section class="installer auth-section rounded overflow-hidden">
                        <div class="installer-content auth-content ">
                            <div class="installer-header-box">
                                <div class="installer-header text-center clearfix">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="panel-heading text-center p-5">
                                                <h2>Welcome To WebERP / WebPOS</h2>
                                                <h2>WebERP / WebPOS — Business & Company Management Solution with POS (SaaS) Laravel
                                                    Script Installation</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="installer-content-box p-5">
                                    <!-- progressbar -->
                                    <div class="progress-menus">
                                        <ul id="progressbar">
                                            <li class="active" id="welcome-setup-tab"></li>
                                            <li id="pre-installation-tab"></li>
                                            <li id="database-configuration-tab"></li>
                                            <li id="administration-tab"></li>
                                        </ul>
                                    </div>
                                    <form action="{{ route('installing') }}" method="post">
                                        @csrf
                                        <div class="tab-content gy-4 py-3">
                                            @include('installer::welcome_setup')
                                            @include('installer::pre_installation')
                                            @include('installer::database_config')
                                            @include('installer::administration')
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
        </div>
        </div>
        <script src="{{ static_asset('backend/assets') }}/js/jquery-3.6.0.min.js"></script>
        <script src="{{ static_asset('backend/assets') }}/js/bootstrap.bundle.min.js"></script>
        @include('installer::stepper_js')
    </body>

    </html>
