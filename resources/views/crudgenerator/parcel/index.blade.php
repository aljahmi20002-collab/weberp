@extends('backend.partials.master')

@section('maincontent')
<div class="user-panel-wrapper">
    <div class="user-panel-content">
        <div class="row">


            <div class="col-md-12">
                <div class="dashboard--widget ">
                <div style="display:inline">
                    <h4 class="card-title" style="display:inline-block">Parcel</h4>
                    <a href="{{ url('/parcel/create') }}" class="btn btn-primary btn-sm float-right p-2" style="margin-left:10px" title="Add New Parcel">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>

                    <form method="GET" action="{{ url('/parcel') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                            <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                    <div class=" ">

                        <br/>
                        <br/>
                        <div class="table-responsive table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name</th><th>Email</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($parcel as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->email }}</td>
                                        <td>
                                            <a href="{{ url('/parcel/' . $item->id) }}" title="View Parcel"><button class="action-btn  text-success"><i class="fa fa-eye"  ></i></button></a>
                                            <a href="{{ url('/parcel/' . $item->id . '/edit') }}" title="Edit Parcel"><button class="action-btn"><i class="fas fa-pen"></i></button></a>

                                            <form method="POST" action="{{ url('/parcel' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="action-btn  text-danger" title="Delete Parcel" onclick="return confirm(&quot;Confirm delete?&quot;)">   <i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $parcel->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
