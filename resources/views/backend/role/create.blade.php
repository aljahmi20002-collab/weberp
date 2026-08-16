@extends('backend.partials.master')
@section('title','Role create')

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('role') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('role.index') }}">{{ __('role') }}</a> </li>
            <li>  {{ __('create') }} </li>
        </ul>
    </div>
@endsection


@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                        <h4 class="card-title overflow-hidden">

                            <a href="{{ route('role.index') }}" class="btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('role.store') }}" method="post">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                                            @error('name')
                                                <p class="text-danger pt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12  pt-25">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label cmr-10">{{ __('status') }}</label>
                                                <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }}>
                                                <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <!-- Responsive Dashboard Table -->
                                    <div class="table-responsive   rolePermissions">
                                        <table class="table table-striped table-hover text-left">
                                            <thead>
                                                <tr class="border-bottom text-left">
                                                    <th>{{ __('modules') }}</th>
                                                    <th>{{ __('permissions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions as $permission) {{-- permissions show --}}
                                                    <tr class="text-left">
                                                        <td class="rolePermissions-module" >{{ __($permission->attributes) }}</td>
                                                        <td  class="rolePermissions-permissions">
                                                            <div class="row">
                                                                @foreach ($permission->keywords as $key=>$keyword )
                                                                    <div class=" col-lg-12 col-sm-6 col-md-4">
                                                                        <div class="row align-items-center permission-check-box pb-2 pt-2"  >
                                                                            <input id="{{ $keyword }}" class="read common-key" type="checkbox" value="{{ $keyword }}" name="permissions[]"  />
                                                                            <label for="{{ $keyword }}" class="permission-check-lebel">{{ __($key) }}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Responsive Dashboard Table -->
                                </div>
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn-sm submit-btn btn-primary btn-lg"> <i class="fa fa-save"></i> {{__('save')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script  src="{{ static_asset('backend') }}/js/role/role.js"></script>
@endpush
