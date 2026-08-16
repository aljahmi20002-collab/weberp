@extends('backend.partials.master')
@section('title')
    {{ __('user') }} {{ __('permissions') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('user') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('user.index') }}">{{ __('user') }}</a> </li>
            <li>  {{ __('permissions') }} </li>
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
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('user.permissions.update',['user_id'=>$user->id]) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="row mt-3">
                            <div class="col-lg-12">
                                    <!-- Responsive Dashboard Table -->
                                    <div class="table-responsive ">
                                        <table class="table table-striped table-hover text-left">
                                            <thead>
                                                <tr class="border-bottom text-left">
                                                    <th>{{ __('modules') }}</th>
                                                    <th>{{ __('permissions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions as $permission) {{-- permissions show --}}
                                                        @php $count=0; @endphp
                                                        @foreach ($permission->keywords as $key=>$check )
                                                            @if(in_array($check,Auth::user()->permissions))
                                                              @php $count += 1; @endphp
                                                            @endif
                                                        @endforeach
                                                    @if($count > 0)
                                                        <tr class="text-left p-10-0">
                                                            <td class="rolePermissions-module">{{ __($permission->attributes) }}</td>
                                                            <td  class="rolePermissions-permissions">
                                                                <div class="row">
                                                                    @foreach ($permission->keywords as $key=>$keyword ) 
                                                                        @if($user->user_type == \App\Enums\UserType::SUPERADMIN)
                                                                            <div class="col-lg-12 col-sm-6 col-md-4">
                                                                                <div class="row align-items-center permission-check-box pb-2 pt-2"  >
                                                                                    <input id="{{ $keyword }}" class="read common-key" type="checkbox" value="{{ $keyword }}" name="permissions[]" @if(in_array($keyword,$user->permissions)) checked @endif />
                                                                                    <label for="{{ $keyword }}" class="permission-check-lebel">{{ __($key) }}</label>
                                                                                </div>
                                                                            </div>
                                                                        @elseif(in_array($keyword,Auth::user()->permissions)) 
                                                                            <div class="col-lg-12 col-sm-6 col-md-4">
                                                                                <div class="row align-items-center permission-check-box pb-2 pt-2"  >
                                                                                    <input id="{{ $keyword }}" class="read common-key" type="checkbox" value="{{ $keyword }}" name="permissions[]" @if(in_array($keyword,$user->permissions)) checked @endif />
                                                                                    <label for="{{ $keyword }}" class="permission-check-lebel">{{ __($key) }}</label>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Responsive Dashboard Table -->
                                </div>
                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn submit-btn btn-primary btn-sm"><i class="fa fa-save"></i> {{__('update')}}</button>
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
