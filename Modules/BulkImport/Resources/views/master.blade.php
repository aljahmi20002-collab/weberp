@extends('backend.partials.master') 
@section('maincontent')
<div class="user-panel-wrapper">
    <div class="user-panel-content">
        <div class="row g-4">
            <div class="col-xl-12">
                <div class="dashboard--widget "> 
                    <div class="text-right">
                       @stack('excel-import')
                    </div>
                    <div id="bulk-import"  ></div>
                    <button type="button" class="btn btn-sm btn-primary mt-3">{{ __('submit') }}</button>   
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ static_asset('backend/vendor/handsontable') }}/handsontable.full.min.css">  
@endpush
@push('scripts')
    <script src="{{ static_asset('backend/vendor/handsontable') }}/handsontable.full.min.js"></script>
    @stack('bulk_script')
@endpush