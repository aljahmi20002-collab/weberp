@extends('backend.partials.master')
@section('title')
    {{ __('edit') }} {{ __('phrase') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('languages') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('language.index') }}">{{ __('languages') }}</a> </li>
            <li> {{ __('edit') }} {{ __('phrase') }}</li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden">{{ __('edit') }} {{ __('phrase') }}

                            <a href="{{ route('language.index') }}" class="btn btn-sm btn-primary float-right"
                                data-bs-toggle="tooltip" title="{{ __('back') }}" data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('language.update.phrase', [$lang->code]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="table-responsive table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th>#</th>
                                            <th>{{ __('phrase') }}</th>
                                            <th>{{ __('translated_language') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                        @endphp

                                        @foreach ($langData as $key => $value)
                                            <tr>
                                                <td data-title="#">{{ ++$i }}</td>
                                                <td data-title="phrase">{{ __($key) }}</td>
                                                <td data-title="translated_language">
                                                    <input type="text" class="form-control form--control"
                                                        name="{{ $key }}" value="{{ $value }}" />
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 mt-5 text-end">
                                <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i>
                                    {{ __('update') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
