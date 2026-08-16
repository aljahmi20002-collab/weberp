@extends('backend.partials.master')
@section('title', __('backup'))

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('backup') }}</h5>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <p class="mt-3">{{ __('backup_message') }}</p>
                        <a class="btn btn-sm btn-primary" href="{{ route('backup.download') }}">{{ __('download') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
