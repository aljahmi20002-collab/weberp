@extends('backend.partials.master')
@section('title')
    {{ __('general_settings') }}
@endsection

@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('general_settings') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="{{ route('user.index') }}">{{ __('crud_generator') }}</a> </li>
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
                        <h4 class="card-title overflow-hidden">{{ __('crud_generator') }}</h4>
                        <form action="{{ route('crud.generator.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                                <div class="row mt-3">

                                    <div class="col-lg-3">
                                        <label for="title" class="form-label">{{ __('title') }}</label>
                                        <input type="text" name="title" class="form-control form--control " id="title" value="{{ old('title') }}">
                                        @error('title')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="model_name" class="form-label">{{ __('model_name') }}</label>
                                        <input type="text" name="model_name" class="form-control form--control " id="model_name" value="{{ old('model_name') }}">
                                        @error('model_name')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="table_name" class="form-label">{{ __('table_name') }}</label>
                                        <input type="text" class="form-control form--control" id="table_name" disabled >
                                    </div>


                                    <div class="col-lg-3">
                                        <label for="module_icon" class="form-label">{{ __('module_icon_class') }}</label>
                                         <select class="form-control form--control" id="module_icon" name="icon_class" >
                                             @foreach (icon_class() as $class)
                                                <option value="{{ $class }}">{{ $class }}</option>
                                             @endforeach
                                         </select>

                                        @error('module_icon')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror

                                    </div>


                                </div>

                                <h4 class="card-title overflow-hidden mt-5">{{ __('add_field') }}</h4>
                                <div class="row">
                                    <div class="col-lg-3"><label for="field_name" class="form-label">{{ __('field_name') }}</label></div>
                                    <div class="col-lg-3"><label for="field_name" class="form-label">{{ __('db_type') }}</label></div>
                                    <div class="col-lg-3"><label for="field_name" class="form-label">{{ __('html_type') }}</label></div>
                                    <div class="col-lg-3"><label for="field_name" class="form-label">{{ __('action') }}</label></div>
                                </div>
                                <div id="field_row"></div>
                                <button type="button" class="btn btn-primary" id="add_field_btn">{{ __('add_field') }}</button>
                                @if(hasPermission('crud_generator_create'))
                                    <div class="col-md-12 mt-5 text-end">
                                        <button type="submit" class="btn submit-btn btn-primary btn-lg"> {{__('generate')}}</button>
                                    </div>
                                @endif
                            </div>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')

        <script type="text/javascript">
            var i = 0;
            $('#add_field_btn').click(function(){
                i++;
                var row ="";
                row += '<div class="row mt-3">';
                row +=  '<div class="col-lg-3">';
                row += '<input type="text" name="field['+i+'][field_name]" class="form-control form--control" id="field_name" value="{{ old("field_name") }}">';
                @error('field_name')
                row += '<p class="text-danger pt-2">{{ $message }}</p>';
                @enderror
                row += '</div>';

                row += '<div class="col-lg-3">';
                row += '<select class="form-control form--control" name="field['+i+'][db_type]" height="40px" id="db_type">';
                row +='<option value="increments"  >INCREMENTS</option>';
                row +='<option value="bigIncrements"  >BIG INCREMENTS</option>';
                row +='<option value="timestamps"  >TIME STAMPS</option>';
                row +='<option value="softDeletes"  >SOFT DELETES</option>';
                row +='<option value="rememberToken"  >REMEMBER TOKEN</option>';
                row +='<option disabled="disabled"  >-</option>';
                row +='<option value="string" selected >STRING</option>';
                row +='<option value="text"  >TEXT</option>';
                row +='<option disabled="disabled"  >-</option>';
                row +='<option value="tinyInteger"  >TINY INTEGER</option>';
                row +='<option value="smallInteger"  >SMALL INTEGER</option>';
                row +='<option value="mediumInteger"  >MEDIUM INTEGER</option>';
                row +='<option value="integer"  >INTEGER</option>';
                row +='<option value="bigInteger"  >BIG INTEGER</option>';
                row +='<option disabled="disabled"  >-</option>';
                row +='<option value="float"  >FLOAT</option>';
                row +='<option value="decimal"  >DECIMAL</option>';
                row +='<option value="boolean"  >BOOLEAN</option>';
                row +='<option disabled="disabled"  >-</option>';
                row +='<option value="enum"  >ENUM</option>';
                row +='<option disabled="disabled"  >-</option>';
                row +='<option value="date"  >DATE</option>';
                row +='<option disabled="disabled"  >-</option>';
                row +='<option value="binary"  >BINARY</option>';
                row +='</select>';

                @error('db_type')
                row +='<p class="text-danger pt-2">{{ $message }}</p>';
                @enderror
                row +='</div>';
                row +='<div class="col-lg-3">';
                row +='<select class="form-control form--control" name="field['+i+'][html_type]" height="40px" id="db_type">';

                row +='<option value="text" >Text</option>';
                row +='<option value="email" >Email</option>';
                row +='<option value="number" >Number</option>';
                row +='<option value="date" >Date</option>';
                row +='<option value="file" >File</option>';
                row +='<option value="password" >Password</option>';
                row +='<option value="select" >Select</option>';
                row +='<option value="radio" >Radio</option>';
                row +='<option value="checkbox" >Checkbox</option>';
                row +='<option value="textarea" >TextArea</option>';
                row +='</select>';
                @error('html_type')
                row +='<p class="text-danger pt-2">{{ $message }}</p>';
                @enderror
                row +='</div>';

                row +='<div class="col-lg-3">';
                row +='<div>';
                row +='<button type="button" id="actionBtn" class="action-btn text-danger mt-2" >';
                row +='<i class="fas fa-trash-alt" style="font-size: 20px"></i>';
                row +='</button>';
                row += '</div>';
                row +='</div>';
                row +='</div>';

                $('#field_row').append(row);
                $('.action-btn').on('click',function(){
                    $(this).parent().parent().parent().remove();
                });

            });


        </script>
        <script src="{{static_asset('backend')}}/js/crud_generator.js"></script>
    @endpush
@endsection


