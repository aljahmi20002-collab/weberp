@extends('pos::master')
@section('title',__('print'))
@section('maincontent')  
    <div class="row printsbtn">
        <div class="col-12 text-right mt-3">
            <button class="btn btn-primary" type="button" onclick="window.print()"><i class="fa fa-print m-1"></i>{{ __('print') }}</button> <button class="btn btn-danger" type="button" onclick="window.close()"><i class="fa fa-close m-1"></i>{{ __('close') }}</button>
        </div>
    </div>
    @include('pos::sale_print',['pos'=>$pos])  
@endsection 
@push('styles')
    <link rel="stylesheet" href="{{static_asset('backend')}}/css/pos/pos_print.css">
@endpush 
@push('scripts')
    <script src="{{ static_asset('backend') }}/js/pos/pos.js"></script> 
@endpush