
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{static_asset('backend/js/select2')}}/select2.min.css">
<script  src="{{static_asset('backend/js')}}/select2/select2.min.js"></script>
<script type="text/javascript"> 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function variationRemove(){
        $('.variation_remove').click(function(){
            $(this).parents('table.product-create').parent().parent().remove();
        });
    }
    variationRemove();

    function variationvaluerowRemove(){
        $('.variation_value_row_remove').click(function(){
            $(this).parent().parent().remove();
        });
    }
    variationvaluerowRemove(); 
    $(document).ready(function(){ 
        $('.select'+{{ $id }}).select2(); 
        $('#vari_select{{ $id }}').change(function(){
            var url         = $(this).data('url');
            var variation_id = $(this).val(); 
            $.ajax({
                url: url,
                method: 'post',
                dataType: 'html',
                data:{ 
                    variation_id: variation_id, 
                },
                success: (response) => { 
                    $('#variation_value{{ $id }}').html(response);
                },
                error: (error) => {  
                    console.log(error);
                }
            })
        }); 


        var t = 1;
        $('#variationValueAdd{{ $id }}').click(function(){
            ++t; 
            var business_id = $('#business_id').val(); 
            $.ajax({
                url: $(this).data('url'),
                method: 'post',
                dataType: 'html',
                data:{ 
                    business_id:business_id,
                    variation_id:$('#vari_select{{ $id }}').val(), 
                    id:t
                },
                success: (response) => { 
                    $('#prductCreate{{ $id }}').append(response);
                },
                error: (error) => {  
                    console.log(error);
                }
            }) 
        });

        //selling  price calculation
        function purchesPrice(thises){
            var purchese_price = $(thises).val();
            $('.purchese_price_{{ $random_number }}').val(purchese_price); 
            var mergin        = $('#purches_mergin_{{ $random_number }}').val();
            if(mergin ===''){
                mergin = 0;
                $('#purches_mergin_{{ $random_number }}').val(0);
            } 
            if(purchese_price ===''){
                purchese_price = 0;
                $('.purchese_price_{{ $random_number }}').val(0); 
            }
            var tax = (parseFloat(purchese_price)/100) * parseFloat(mergin);
            var selling_price =( parseFloat(purchese_price)+ parseFloat(tax));
            var price = parseFloat(selling_price).toFixed(2);   
            $("#selling_price_{{ $random_number }}").val(price);
        }

        $('.purchese_price_{{ $random_number }}').on('keyup', function(){
            var thises = $(this);
            purchesPrice(thises);
        });

        $('#purches_mergin_{{ $random_number }}').on('change', function(){
            var thises = $('.purchese_price_{{ $random_number }}');
            purchesPrice(thises);
        });

    });

</script>
 