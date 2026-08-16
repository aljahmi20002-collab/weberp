@extends('backend.partials.master')

@section('maincontent')
     <div class="user-panel-wrapper">
        <div class="user-panel-content">
        <div class="row">


            <div class="col-md-12">
                <div class="dashboard--widget ">
                    <div style="overflow:hidden">
                        <h4  style="display:inline-block">Parcel {{ $parcel->id }}</h4>
                        <div class="float-right" style="display:inline-block">
                            <a href="{{ url('/parcel') }}" title="Back" ><button class="btn btn-warning btn-sm float-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                            <a href="{{ url('/parcel/' . $parcel->id . '/edit') }}" title="Edit Parcel"><button class="btn btn-primary btn-sm float-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                            <form method="POST" action="{{ url('parcel' . '/' . $parcel->id) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm float-right" title="Delete Parcel" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="">

                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $parcel->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $parcel->name }} </td></tr><tr><th> Email </th><td> {{ $parcel->email }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
