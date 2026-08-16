<tr>
    <td width="10%">
        <select class="variation{{ $variation_id }}selected{{ $id }}" id="variation_values{{ $id }}">{!! $options !!}</select>
    </td>
    <td> 
        <input type="text" class="form-control form--control variation-input"/> 
    </td>
    <td>
        <div class="d-flex">
            <input type="text" class="form-control form--control variation-input item_purchese_price_{{ $random_number2 }} "/>
            <input type="text" class="form-control form--control variation-input  ml-2 item_purchese_price_{{ $random_number2 }}"/>
        </div>
    </td>
    <td> 
        <input type="text" class="form-control form--control variation-input " value="{{ $default_mergin }}" id="item_purches_mergin_{{ $random_number2 }}"/>
    </td>
    <td>
        <input type="text" class="form-control form--control variation-input" id="item_selling_price_{{ $random_number2 }}"/>
    </td>
    <td>
        <input type="file" class="form-control form--control variation-input"/>
    </td>
    <td>
       <label   class="variation_value_row_remove"><i class="fa fa-trash"></i></label>
    </td>
    <td> </td>
</tr>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{static_asset('backend/js/select2')}}/select2.min.css">
<script  src="{{static_asset('backend/js')}}/select2/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.variation{{ $variation_id }}selected{{ $id }}').select2(); 
        function variationvaluerowRemove(){
            $('.variation_value_row_remove').click(function(){
                $(this).parent().parent().remove();
            });
        }
        variationvaluerowRemove();
        
            //selling  price calculation
            function purchesPrice(thises){
                var purchese_price = $(thises).val();
                $('.item_purchese_price_{{ $random_number2 }}').val(purchese_price); 
                var mergin        = $('#item_purches_mergin_{{ $random_number2 }}').val();
                if(mergin ===''){
                    mergin = 0; 
                    $('#item_purches_mergin_{{ $random_number2 }}').val(0)
                } 
                if(purchese_price ===''){
                    purchese_price = 0;
                    $('.item_purchese_price_{{ $random_number2 }}').val(0); 
                }
                var tax = (parseFloat(purchese_price)/100) * parseFloat(mergin);
                var selling_price =( parseFloat(purchese_price)+ parseFloat(tax));
                var price = parseFloat(selling_price).toFixed(2);   
                $("#item_selling_price_{{ $random_number2 }}").val(price);
            }

            $('.item_purchese_price_{{ $random_number2 }}').on('keyup', function(){
                var thises = $(this);
                purchesPrice(thises);
            });

            $('#item_purches_mergin_{{ $random_number2 }}').on('change', function(){
                var thises = $('.item_purchese_price_{{ $random_number2 }}');
                purchesPrice(thises);
            });

    });
</script>