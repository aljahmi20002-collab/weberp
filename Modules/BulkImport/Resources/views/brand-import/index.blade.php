@extends('bulkimport::master')
@section('title')
    {{ __('bulk') }} {{ __('brand') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('brand') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('import') }}</a> </li>
            <li>  {{ __('bulk') }} {{ __('brand') }} </li>
        </ul>
    </div>
@endsection 
@push('excel-import') 
    <a class="btn btn-sm btn-primary my-2" href="{{ route('bulk.import.brand.excel.index') }}">{{ __('import') }}</a>
@endpush
@push('bulk_script')
    <script type="text/javascript"> 
            const container = document.querySelector('#bulk-import');  

            const generateData = (rows = 18, columns = 5, additionalRows = true) => {
            const array2d = [...new Array(rows)]
                .map(_ => [...new Array(columns)]
                .map(_ => ''));

                if (additionalRows) {
                    array2d.push([]);
                    array2d.push([]);
                } 
                return array2d;
            }; 
   
            function nameValidator (value,callback){ 
                var names = value.toString().length;
                if (names > 0) {  
                    callback(true);
                } else {  
                    callback(false);
                }
            }

            const hot = new Handsontable(container, {
            data: generateData(), 
            columns: [ 
                    {validator:nameValidator},
                    { width:'100',  },
                    { },
                    { type: 'numeric'}, 
                ], 
 
            colHeaders: ['Name *','Logo Image Link', 'Description','Position'],
            width: '100%',
            height: 'auto', 
            rowHeaders: true,
            stretchH: 'all', // 'none' is default
            contextMenu: true,
            activeHeaderClassName: 'ht__active_highlight', 
            licenseKey: 'non-commercial-and-evaluation',  
            });
 
           

            $('button').on('click',function(){ 

                $(this).html('loading...');
                $(this).attr('disabled','disabled'); 

                var data = hot.getData();
                $.ajax({
                    url: "{{ route('bulk.import.brand.store') }}",
                    method: 'post',
                    dataType: 'json',
                    data:{  
                        data:data
                    },
                    success: (response) => { 
                        $(this).html('submit');
                        $(this).removeAttr('disabled'); 
 
                         if(response.success == null){  
                                toastr.error('{{ __("no_brand_has_been_placed_yet") }}',"{{ __('errors') }}");
                         }else if(response.success == true){ 
                            toastr.success('{{ __("brand_import_successfully") }}',"{{ __('success') }}");
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }else{ 
                             toastr.success('{{ __("something_went_wrong") }}',"{{ __('errors') }}");
                         }
                    },
                    error: (error) => {   
                        toastr.success('{{ __("something_went_wrong") }}',"{{ __('errors') }}");
                        $(this).html('submit');
                        $(this).removeAttr('disabled'); 
                    }
                }) 
            }); 
    </script>
@endpush