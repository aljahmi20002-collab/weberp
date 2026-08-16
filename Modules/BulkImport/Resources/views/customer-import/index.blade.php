@extends('bulkimport::master')
@section('title')
    {{ __('bulk') }} {{ __('customer') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('customer') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('customer') }}</a> </li>
            <li>  {{ __('bulk') }} {{ __('customer') }} </li>
        </ul>
    </div>
@endsection 
@push('excel-import') 
    <a class="btn btn-sm btn-primary my-2" href="{{ route('bulk.import.customer.excel.index') }}">{{ __('import') }}</a>
@endpush
@push('bulk_script')
    <script type="text/javascript"> 
            const container = document.querySelector('#bulk-import');   
            const generateData = (rows = 18, columns = 5, additionalRows = true) => {
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
                    { }, 
                    {
                        type:'numeric', 
                        width:'100'
                    }, 
                ], 
 
            colHeaders: ['Name *','Phone ', 'Email *','Image Link','Address', 'Opening Balance'], 
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
                    url: "{{ route('bulk.import.customer.store') }}",
                    method: 'post',
                    dataType: 'json',
                    data:{  
                        data:data
                    },
                    success: (response) => { 
                        $(this).html('submit');
                        $(this).removeAttr('disabled'); 
                        
                         if(response.success == null){  
                                toastr.error('{{ __("no_customer_has_been_placed_yet") }}',"{{ __('errors') }}");
                         }else if(response.success == true){ 
                            toastr.success('{{ __("customer_import_successfully") }}','{{ __("success") }}');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }else{ 
                            toastr.error('{{ __("something_went_wrong") }}','{{ __("errors") }}');
                         }
                    },
                    error: (error) => {   
                        toastr.error('{{ __("something_went_wrong") }}','{{ __("errors") }}');
                        $(this).html('submit');
                        $(this).removeAttr('disabled'); 
                        
                    }
                }) 
            }); 
    </script>
@endpush