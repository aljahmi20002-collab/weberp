<!DOCTYPE html>
<html lang="en" dir={{ languageDirection(app()->getLocale()) }}>

<head id="head">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ settings('favicon') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/animate.css">
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/all.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend/css') }}/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/jquery-ui.css">
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/sweetalert2.css">
    <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/dropzone.css">
    @if (languageDirection(app()->getLocale()) == 'rtl')
        <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/main-with-rtl.css">
    @else
        <link rel="stylesheet" href="{{ static_asset('backend/assets') }}/css/main.css">
    @endif
    <link rel="stylesheet" href="{{ static_asset('backend') }}/css/sweetalert2.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/assets/css/flag-icon-css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend') }}/css/custom.css">
    <link rel="stylesheet" href="{{ static_asset('backend/js/select2') }}/select2.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend/vendor/summernote') }}/summernote-lite.min.css">
    <link rel="stylesheet" href="{{ static_asset('backend/vendor/toastr') }}/toastr.min.css" />
    <link rel="stylesheet" href="{{ static_asset('backend/vendor/datatable') }}/jquery.dataTables.css" />
 
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @stack('styles')
    @include('backend.partials.theme_color_dynamic')

</head>

<body>
