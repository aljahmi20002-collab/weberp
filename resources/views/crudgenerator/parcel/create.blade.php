@extends('backend.partials.master')

@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row">

                <div class="col-md-12">
                    <div class="dashboard--widget ">
                        <div style="display:inline">
                            <h4 class="" style="display:inline-block">Create New Parcel</h4>
                            <a href="{{ url('/parcel') }}" title="Back" class="float-right"><button class="btn btn-warning btn-sm "><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        </div>
                        <div class="">
                            <br />
                            <br />

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <form method="POST" action="{{ url('/parcel') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6">
                                        @include ('crudgenerator.parcel.form', ['formMode' => 'create'])
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
