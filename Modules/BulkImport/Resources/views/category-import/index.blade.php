@extends('bulkimport::master')
@section('title')
    {{ __('bulk') }} {{ __('category') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('category') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('import') }}</a> </li>
            <li>  {{ __('bulk') }} {{ __('category') }} </li>
        </ul>
    </div>
@endsection 
@push('excel-import') 
    <a class="btn btn-sm btn-primary my-2" href="{{ route('bulk.import.category.excel.index') }}">{{ __('import') }}</a>
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
            var categories = [];

            function nameValidator (value,callback){ 
                var names = value.toString().length;
                if (names >=1) {  
                    callback(true);
                } else {  
                    callback(false);
                }
            }

            function categoryValidator(value,callback){ 
                if(value.toString().length  == 0 || categories.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }

            const hot = new Handsontable(container, {
            data: generateData(), 
            columns: [ 
                    { validator:nameValidator,width:'100' },
                    { width:'100' },
                    { width:'100' },
                    { type: 'numeric',width:'100' }, 
                    { 
                        type: 'autocomplete',
                        source(query,process){
                            $.ajax({
                                url: '{{ route("bulk.import.product.category.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => { 
                                    categories  = response;  
                                    process(categories); 
                                },
                                error: (error) => {  
                                    process([]);
                                }
                            });  
                        },
                        validator:categoryValidator,
                        width:'100' 
                    }
                ], 
            colHeaders: ['Name *','Image Link', 'Description','Position','Cateogry'],
             
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
                    url: "{{ route('bulk.import.category.store') }}",
                    method: 'post',
                    dataType: 'json',
                    data:{  
                        data:data
                    },
                    success: (response) => { 
                        $(this).html('submit');
                        $(this).removeAttr('disabled'); 
                      
                         if(response.success == null){  
                            toastr.error('{{ __("no_category_has_been_placed_yet") }}','{{ __("errors") }}');
                         }else if(response.success == true){ 
                            toastr.success('{{ __("category_import_successfully") }}','{{ __("success") }}');
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