@extends('bulkimport::master')
@section('title')
    {{ __('bulk') }} {{ __('supplier') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('supplier') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('supplier') }}</a> </li>
            <li>  {{ __('bulk') }} {{ __('supplier') }} </li>
        </ul>
    </div>
@endsection 
@push('excel-import') 
    <a class="btn btn-sm btn-primary my-2" href="{{ route('bulk.import.supplier.excel.index') }}">{{ __('import') }}</a>
@endpush
@push('bulk_script')
    <script type="text/javascript"> 
            const container = document.querySelector('#bulk-import');   
            const generateData = (rows = 18, columns = 6, additionalRows = true) => {
            const array2d = [...new Array(rows)]
                .map(_ => [...new Array(columns)]
                .map(_ => null));

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
            function numberValidator (value,callback){ 
                var numbers = value.toString().length;
                if (numbers < 14 && numbers >= 10) {  
                    callback(true);
                } else {  
                    callback(false);
                }
            }

            function emailValidator(value,callback){
                if(value.length == 0 || value.includes('@')){
                    return callback(true);
                }else{
                    return callback(false);
                }
            } 
  
            const hot = new Handsontable(container, {
            data: generateData(),  
            columns: [ 
                    {width:'100',validator:nameValidator},
                    {width:'100'},
                    {  
                        type:'numeric',
                        validator:  numberValidator ,
                        width:'100'
                    }, 
                    { 
                        validator:emailValidator ,
                        width:'100'
                    }, 
                    { width:'100' },  
                    {
                        type:'numeric', 
                        width:'100'
                    }, 
                ], 
   
            colHeaders: ['Name *','Company Name','Phone *', 'Email', 'Address', 'Opening Balance'], 
            width: '100%',
            height: 'auto', 
            rowHeaders: true,
            stretchH: 'all', // 'none' is default
            contextMenu: true,
            activeHeaderClassName: 'ht__active_highlight', 
            licenseKey: 'non-commercial-and-evaluation',  
            });
 
            $('button').on('click',function(){  
                $(this).html('Loading...');
                $(this).attr('disabled','disabled');
                var data = hot.getData(); 
                $.ajax({
                    url: "{{ route('bulk.import.supplier.store') }}",
                    method: 'post',
                    dataType: 'json',
                    data:{  
                        data:data
                    },
                    success: (response) => { 
                        $(this).html('Submit');
                        $(this).removeAttr('disabled');
                      
                         if(response.success == null){  
                            toastr.error('{{ __("no_supplier_has_been_placed_yet") }}','{{ __("errors") }}');
                         }else if(response.success == true){ 
                            toastr.success( '{{ __("supplier_import_successfully") }}','{{__("success")}}');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }else{ 
                             toastr.error('{{ __("something_went_wrong") }}','{{ __("errors") }}');
                         }
                    },
                    error: (error) => {  
                        toastr.error('{{ __("something_went_wrong") }}','{{ __("errors") }}'); 
                        $(this).html('Submit');
                        $(this).removeAttr('disabled');
                    }
                }) 
            }); 
    </script>
@endpush