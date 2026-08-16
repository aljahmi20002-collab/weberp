@extends('backend.partials.master')
@section('title')
    {{ __('user') }} {{ __('create') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('user') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('user.index') }}">{{ __('user') }}</a> </li>
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

                            <a href="{{ route('user.index') }}" class="btn-sm btn-primary float-right" data-bs-toggle="tooltip" title="{{ __('back') }}"
                            data-bs-placement="top">
                                <i class="fa fa-arrow-left"></i> {{ __('back') }}
                            </a>
                        </h4>
                        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                        
                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="email" class="form-label">{{ __('email') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form--control" id="email" value="{{ old('email') }}">
                                    @error('email')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="col-lg-3 mt-3">
                                    <label for="user_type" class="form-label">{{ __('user_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="user_type" id="user_type">
                                        <option selected disabled>{{ __('select')}} {{ __('user_type') }}</option>
                                        @foreach (\Config::get('pos_default.user_type') as $key=>$usertype)
                                            @if(isSuperadmin())
                                                @if($usertype != \App\Enums\UserType::USER)
                                                <option value="{{ $usertype }}" {{ old('user_type') == $usertype ? 'selected':'' }}>{{ @user_type_text($usertype) }}</option>
                                                @endif
                                            @elseif(isUser())
                                              @if($usertype == \App\Enums\UserType::USER)
                                                    <option value="{{ $usertype }}" {{ old('user_type') == $usertype ? 'selected':'' }}>{{ @user_type_text($usertype) }}</option>
                                              @endif
                                            @else
                                                @if($usertype !== \App\Enums\UserType::SUPERADMIN)
                                                <option value="{{ $usertype }}" {{ old('user_type') == $usertype ? 'selected':'' }}>{{ @user_type_text($usertype) }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('user_type')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                

                                <div class="col-lg-3 mt-3">
                                    <div class="row"> 
                                        <div class="col-sm-5">
                                            <div class="pt-25">
                                                <div class="d-flex mt-3">
                                                    <label class="form-label cmr-10">{{ __('status') }}</label>
                                                    <input type="checkbox" class="status" id="status" switch="success" name="status" {{ old('status',\App\Enums\Status::ACTIVE) == \App\Enums\Status::INACTIVE? '':'checked' }} >
                                                    <label for="status" data-on-label="{{ __('status.'.App\Enums\Status::ACTIVE) }}" data-off-label="{{ __('status.'.App\Enums\Status::INACTIVE) }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                @if(isSuperadmin() == true)
                                    <div class="col-lg-6 mt-3">
                                        <label for="business" class="form-label ">{{ __('business') }} <span class="text-danger">*</span></label>
                                        <select class="form-control form--control select2" name="business_id" id="business_id" data-url="{{ route('user.business.branch.fetch') }}">
                                            <option selected disabled>{{ __('select')}} {{ __('business') }}</option>
                                            @foreach ($businesses as $business)
                                                <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected':'' }}>{{ $business->business_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('business_id')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                @if(!isSuperadmin())
                                <div class="col-lg-6 mt-3">
                                    <label for="branch" class="form-label ">{{ __('branch') }} <span class="text-danger">*</span></label>
                                    <select class="form-control form--control select2" name="branch_id" id="branch_id">
                                        <option selected disabled>{{ __('select')}} {{ __('branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @if(old('branch_id') == $branch->id) selected @endif>{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                <div class="col-lg-6 mt-3">
                                    <label for="designation_id" class="form-label ">{{ __('designation') }} </label>
                                    <select class="form-control form--control select2" name="designation_id" id="designation_id">
                                        <option selected disabled>{{ __('select')}} {{ __('designation') }}</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}" @if(old('designation_id') == $designation->id) selected @endif>{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('designation_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="department_id" class="form-label ">{{ __('department') }} </label>
                                    <select class="form-control form--control select2" name="department_id" id="department_id">
                                        <option selected disabled>{{ __('select')}} {{ __('department') }}</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @if(old('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="phone" class="form-label">{{ __('phone') }}</label>
                                    <input type="text" name="phone" class="form-control form--control" id="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mt-3">
                                    <label for="password" class="form-label">{{ __('password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control form--control " id="password" value="{{ old('password') }}">
                                    @error('password')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label for="confirm_password" class="form-label">{{ __('confirm_password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control form--control " id="confirm_password" value="{{ old('confirm_password') }}">
                                    @error('password')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6 text-center mt-3">
                                    <div class="text-left">
                                        <label class="form-label">{{ __('avatar') }}</label>
                                    </div>
                                    <div class="pupload mb-5">
                                        <div class="thumb">
                                            <img class="pu-img" src="{{static_asset('backend/images/default/user.jpg') }}" alt="clients">
                                        </div>
                                        <div class="remove-thumb">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="content">
                                            <div class="mt-2">
                                                <label class="btn btn-sm btn-primary">
                                                    <i class="fas fa-camera"></i> {{ __('change') }}  {{ __('avatar') }}
                                                    <input type="file" id="profile-image-upload" hidden="" name="avatar">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-6 mt-3">
                                            <label for="address" class="form-label">{{ __('address') }} </label>
                                            <textarea  name="address" class="form-control form--control " id="address" >{{ old('address') }}</textarea>
                                            @error('address')
                                                <p class="text-danger pt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 mt-3">
                                            <label for="about" class="form-label">{{ __('about') }}</label>
                                            <textarea  name="about" class="form-control form--control" id="about" >{{ old('about') }}</textarea>
                                            @error('about')
                                                <p class="text-danger pt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>



                                <div class="col-md-12 mt-5 text-end">
                                    <button type="submit" class="btn btn-sm submit-btn btn-primary "> <i class="fa fa-save"></i> {{__('save')}}</button>
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
    <script type="text/javascript">
        $(document).ready(function(){
            var year = '1901:'+new Date().getFullYear();
            $('.dateofbirth').datepicker({
                changeMonth:true,
                dateFormat: 'dd/mm/yy',
                yearRange: year,
                changeYear:true,
            });
        });
    </script>
    <script src="{{static_asset('backend/assets')}}/js/profile.js"></script>
    <script src="{{static_asset('backend/js')}}/user/create.js"></script>

@endpush
