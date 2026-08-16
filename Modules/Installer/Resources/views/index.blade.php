<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width,  minimum-scale=0.8, maximum-scale = 0.8, user-scalable = no , shrink-to-fit=no">
    <link rel="shortcut icon" href=" {{ static_asset('favicon.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/installer/custom_two.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/installer/custom_one.css">
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/all.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/installer/progressbar.css">
    <link rel='stylesheet' type='text/css' href="{{ static_asset('backend/installer/styleone.css') }}" />
    <title>WeERP - Business or company management solution with POS (SaaS)</title>
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
                                                <h2>Welcome To WeERP</h2>
                                                <h2>WeERP - Business or company management solution with POS (SaaS) Laravel
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
