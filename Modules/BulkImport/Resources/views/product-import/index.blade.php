@extends('bulkimport::master')
@section('title')
    {{ __('bulk') }} {{ __('product') }}
@endsection
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('bulk') }} {{ __('product') }}</h5>
        <ul class="breadcrumb">
            <li> <a href="#">{{ __('bulk') }} {{ __('product') }}</a> </li>
            <li>  {{ __('bulk') }} {{ __('product') }} </li>
        </ul>
    </div>
@endsection  
@push('excel-import') 
    <a class="btn btn-sm btn-primary my-2" href="{{ route('bulk.import.product.excel.index') }}">{{ __('import') }}</a>
@endpush
@push('bulk_script')
    <script type="text/javascript"> 
            const container = document.querySelector('#bulk-import');   
            const generateData = (rows = 13, columns = 17, additionalRows = true) => {
            const array2d = [...new Array(rows)]
                .map(_ => [...new Array(columns)]
                .map(_ => null)); 
                if (additionalRows) {
                    array2d.push([]);
                    array2d.push([]);
                } 
                return array2d;
            }; 

            Array.prototype.insert = function(index) {
                this.splice.apply(this, [index, 0].concat(
                    Array.prototype.slice.call(arguments, 1)));
                return this;
            };

            var columnNames =  ['Name *','Image Link', 'Unit *','Brand *','Warranty','Category *','Subcategory *','Variation *','Variation Value ','Quantity *','Purchase Price *','Profit (%) *','Selling Price *','Tax *','Description'];
            @if (business())
                columnNames.insert(7,'Branch *'); 
            @endif

            var units              = []; 
            var brands             = []; 
            var warranties         = []; 
            var categories         = [];
            var subcategories      = []; 
            var branches           = []; 
            var variations         = []; 
            var variationValues    = []; 
            var taxrate            = []; 


            //start all validation method

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
                if(value.toString().length == 0 || value.includes('@')){
                    return callback(true);
                }else{
                    return callback(false);
                }
            } 
            function unitValidator(value,callback){
                if(value.toString().length > 0 && units.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
           function brandValidator(value,callback){
                if(value.toString().length > 0 && brands.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
           function warrantyValidator(value,callback){
                if(value.toString().length == 0 || warranties.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }

           function categoryValidator(value,callback){ 
                if(value.toString().length > 0 && categories.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
           function subcategoryValidator(value,callback){  
                if(value && value.toString().length > 0 && subcategories.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
  
           function branchValidator(value,callback){ 
                if(value.toString().length > 0 && branches.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }

            function variationValidator(value,callback){ 
                console.log(value,variations); 
                if(value.toString().length > 0 && variations.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
            function variationValueValidator(value,callback){
                 console.log(value);
                if(!value || value.toString().length == 0 || variationValues.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }

   
           function quantityValidator(value,callback){
                if(value > 0 && toString().length > 0){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
           function purchasePriceValidator(value,callback){
                if(value.toString().length > 0){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
   
           function profitValidator(value,callback){
                if(value.toString().length > 0){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
  
           function sellingPriceValidator(value,callback){
                if(value.toString().length > 0 && value >=0){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }

            function taxrateValidator(value,callback){
                if(value.toString().length > 0 && taxrate.includes(value)){
                    return callback(true);
                }else{
                    return callback(false);
                }
            }
   

            //end all validation method

            const hot = new Handsontable(container, {
            data: generateData(),  
            columns: [ 
                    {width:'100',validator:nameValidator},
                    { width:'100'  },
                    {    
                        type:'autocomplete',
                        width:'100', 
                        source(query,process){   
                            $.ajax({
                                url: '{{ route("bulk.import.product.units.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => {  
                                   units = response;
                                  process(response); 
                                },
                                error: (error) => {  
                                    process([]);
                                }
                            }); 
                        }, 
                        validator:unitValidator,
                        strict:true
                    }, 
                    { 
                        type:'autocomplete',
                        source(query,process){   
                            $.ajax({
                                url: '{{ route("bulk.import.product.brand.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => { 
                                    brands = response;
                                  process(response); 
                                },
                                error: (error) => {  
                                    process([]);
                                }
                            }); 
                        }, 
                        strict:true,
                        width:'100',
                        validator:brandValidator, 

                    }, 
                    { 
                        type:'autocomplete',  
                        width:'100',
                        source(query,process){   
                            $.ajax({
                                url: '{{ route("bulk.import.product.warranty.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => {    
                                   warranties  = response; 
                                   process(response); 
                                },
                                error: (error) => {  
                                process([]);
                                }
                            }); 
                        },
                        validator:warrantyValidator
                    },  
                    {
                        type:'autocomplete', 
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
                        strict:true,
                        width:'100',
                        allowInvalid: true,
                        validator:categoryValidator
                    }, 
                    {
                        type:'autocomplete',
                        source:subcategories,
                        strict:true,
                        validator:subcategoryValidator,
                        width:'100'
                    },
                    @if (business()) 
                    { 
                        type:'autocomplete',
                        source(query,process){   
                            $.ajax({
                                url: '{{ route("bulk.import.product.branch.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => { 
                                   branches = response;
                                   process(response); 
                                },
                                error: (error) => {  
                                    process([]);
                                }
                            }); 
                        },
                        validator:branchValidator,
                        strict:true,
                        width:'100'
                    },
                    @endif

                    { 
                        type:'autocomplete',
                        source(query,process){   
                            $.ajax({
                                url: '{{ route("bulk.import.product.variation.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => {    
                                   variations  = response; 
                                   process(response); 
                                },
                                error: (error) => {  
                                    process([]);
                                }
                            }); 
                        },
                        strict:true,
                        validator:variationValidator,
                        width:'100'
                    },
                    { 
                        type:'autocomplete',
                        source:variationValues,
                        strict:true,
                        width:'100',
                        validator:variationValueValidator
                    },
                    {
                        type:'numeric',  
                        width:'100',
                        validator:quantityValidator
                    },
                    { 
                        type:'numeric',
                        width:'100',
                        validator:purchasePriceValidator
                    },
                    {
                        type:'numeric',
                        width:'100',
                        validator:profitValidator
                    },
                    { 
                        type:'numeric',
                        width:'100',
                        validator:sellingPriceValidator
                    }, 
                    {
                        type:'autocomplete',
                        source(query,process){   
                            $.ajax({
                                url: '{{ route("bulk.import.product.taxrate.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json', 
                                success: (response) => {
                                   taxrate = response;  
                                   process(response); 
                                },
                                error: (error) => {  
                                    process([]);
                                }
                            }); 
                        },
                        strict:true,
                        validator:taxrateValidator,
                        width:'100'
                    },
                    { width:'100' }, 
                ], 
                afterChange: (changes,source) => { 
                    if (source === 'loadData' || source === 'internal' || changes.length > 1) {
                        return;
                    } 
                    const [row, prop, oldValue, newValue] = changes[0]; 

                        if(prop === 5){ 
                            //category change after show subcategory list
                            if (!categories.includes(newValue)) {
                                hot.setCellMeta(row, 6, 'source', []);
                                hot.setDataAtRowProp(row, 6, ''); 
                                return;
                            }  
                            $.ajax({
                                url: '{{ route("bulk.import.product.subcategory.get.names") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data:{
                                    'category':newValue
                                },
                                dataType: 'json', 
                                success: (response) => {    
                                    subcategories  = response
                                    hot.setCellMeta(row, 6, 'source', subcategories);
                                    hot.setDataAtRowProp(row, 6, subcategories[0]); 
                                }, 
                            });  
                            //end category change after show subcategory list 
                        }else if(prop === 8){ 
                            //variation change after show variation value list 
                            if (!variations.includes(newValue)) {
                                hot.setCellMeta(row, 9, 'source', []);
                                hot.setDataAtRowProp(row, 9, ''); 
                                return;
                            }  
                            $.ajax({
                                url: '{{ route("bulk.import.product.variation.get.values") }}',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data:{
                                    'name':newValue
                                },
                                dataType: 'json', 
                                success: (response) => {    
                                    variationValues  = response
                                    variationValues.insert(0,'');
                                    hot.setCellMeta(row, 9, 'source', variationValues);
                                    hot.setDataAtRowProp(row, 9, ''); 
                                }, 
                            });  
 
                            //end variation change after show variation value list
                        }
                }, 

            colHeaders: columnNames,  
            width: '100%',
            height: 'auto', 
            rowHeaders: true,
            stretchH: 'all', // 'none' is default
            contextMenu: true,
            activeHeaderClassName: 'ht__active_highlight', 
            licenseKey: 'non-commercial-and-evaluation',  

            });
             

            $('button').on('click',function(){ 
                $(this).html('Loading ...'); 
                $(this).attr('disabled', 'disabled'); 
                var data = hot.getData();  
                console.log(data);
                $.ajax({
                    url: "{{ route('bulk.import.product.store') }}",
                    method: 'post',
                    dataType: 'json',
                    data:{  
                        data:data
                    },
                    success: (response) => { 
                        $(this).html('Submit');
                        $(this).removeAttr('disabled');   
                         if(response.success == null){  
                            toastr.error('{{ __("no_product_has_been_placed_yet") }}','{{ __("errors") }}') ;
                         }else if(response.success == true){ 
                            toastr.success('{{ __("product_import_successfully") }}','{{ __("success") }}');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }else{ 
                             toastr.error('{{ __("something_went_wrong") }}','{{ __("errors") }}') ;
                         }
                    },
                    error: (error) => { 
                        toastr.error('{{ __("something_went_wrong") }}','{{ __("errors") }}') ; 
                        $(this).html('submit');
                        $(this).removeAttr('disabled');  
                    }
                }) 
            }); 


    </script>
@endpush