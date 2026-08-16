 
 @foreach ($products as $variation_location) 
    <div class="col-6 col-md-6 col-sm-6 col-lg-3 mt-2"> 
        <div class="card productitem product_variation_item{{ $randomNumber }}" data-url="{{route('pos.variation.location.item',['id'=>$variation_location->id])  }}" data-id="{{ $variation_location->id }}" >
            <div class="card-body"> 
                <img class="lazy" src="{{ @data_get($variation_location->product->images,'image_two') }}" data-original="{{ @data_get($variation_location->product->images,'image_two') }}" width="100%"/>
                <p class="text-center">
                    <small>
                        {{ @$variation_location->product->name }} - {{ @$variation_location->ProductVariation->sub_sku }}<br>
                        (<b>{{ @$variation_location->variation->name }}</b>: {{ @$variation_location->ProductVariation->name }})<br>
                    </small>
                </p>
            </div>
        </div> 
    </div>  
 @endforeach     
<script>
  
//pos item add 
$('.product_variation_item{{ $randomNumber }}').click(function(){ 
    var variation_id = $(this).data('id');
    const ids = $('input[name="variation_item_unique_add[]"]').map(function () {
            return this.value;  
        }).get();
 
    $.ajax({
        url: $(this).data('url'),
        method: 'post',
        dataType: 'html',
        data:{
            'ids':ids
        } 
    })
    .done(function(response){  
        if(ids.includes(variation_id.toString())){
            var quantity         = parseInt($('.qty'+variation_id).val());
            var a = quantity+1;
            if(parseInt($('.qty_available'+variation_id).text()) >= a){
                $('.qty'+variation_id).val(quantity+1);
                var total_quantity   = parseInt($('.qty'+variation_id).val());
                var unit_price       = parseInt($('.unit_price'+variation_id).val());
                var total_unit_price = (total_quantity*unit_price);
                $('.total_unit_price'+variation_id).html(total_unit_price.toFixed(2));
                totalCalculation();

                
                //sound effect
                var audio = $('#success-audio')[0];
                if (audio !== undefined) {
                    audio.play();
                }
                //end sound effect 
                toastr.success("{{ __('product_added_successfully') }}","{{ __('success') }}");  

            }else{

                //sound effect
                var audio = $('#warning-audio')[0];
                if (audio !== undefined) {
                    audio.play();
                }
                //end sound effect 
                toastr.warning("{{ __('product_out_of_stock') }}","{{ __('warning') }}");  
            }
        }else if(response == ''){ 
            //sound effect 
                var audio = $('#warning-audio')[0];
                if (audio !== undefined) {
                    audio.play();
                }   
            //end sound effect 
            toastr.warning("{{ __('product_out_of_stock') }}","{{ __('warning') }}");  
        }
        else{ 
            //sound effect
            var audio = $('#success-audio')[0];
           if (audio !== undefined) {
               audio.play();
           }
           //end sound effect 
           toastr.success("{{ __('product_added_successfully') }}","{{ __('success') }}"); 
            $('#pos_item_content').append(response); 
            

        }
    }) 

    function totalCalculation(){ 
            //total sell cost
            var totalPurchaseCost = 0; 
            $('.totalUnitCost').each(function(index){ 
                totalPurchaseCost += parseInt($(this).text());
            }); 
            var p = totalPurchaseCost.toFixed(2);
            $('#total_purchase_costs').html(p);

            //total quantity
            var quantity          = 0;
            $('.quantity').each(function(index){ 
                quantity += parseInt($(this).val());
            });
            $('#total_item').html(quantity);
            //total amount 

            //discount amount
            var shippingcharge  = parseInt($('#shipping_charge').val());
            var discountamount  = parseInt($('#discount_amount').val());
            if(isNaN(shippingcharge)){
                shippingcharge  = 0;
            }
            if(isNaN(discountamount)){
                discountamount  = 0;
            } 
            $('#total_shipping_charge').html(shippingcharge);
            $('#total_discount_amount').html(discountamount);  
            totalsellPriceWithTax();
    }

    function totalsellPriceWithTax(){ 
            var amount = 0;
            $.ajax({
                url: $('#applicableTax').data('url'),
                method: 'post',
                dataType: 'html',
                data:{  
                    tax_id:$('#applicableTax').val()
                },
                success: (response) => { 

                    var shippingcharge  = parseInt($('#shipping_charge').val());
                    var discountamount  = parseInt($('#discount_amount').val());
                    if(isNaN(shippingcharge)){
                        shippingcharge  = 0;
                    }
                    if(isNaN(discountamount)){
                        discountamount  = 0;
                    } 
                    var taxPercent = parseInt(response); 
                    if(isNaN(taxPercent)){
                        taxPercent  = 0;
                    }
                    var totalPurchaseCost = parseInt($('#total_purchase_costs').text());
                    var taxAmount     = (totalPurchaseCost/100) * taxPercent;
                    var totalPurchaseCostWithTax = (totalPurchaseCost + taxAmount + shippingcharge ) - discountamount;
                    var amount = totalPurchaseCostWithTax.toFixed(2);
                    var txamount = taxAmount.toFixed(2);

                    $('#totlPurchaseTax').html(txamount);
                    $('#totlPurchasePriceWithTax').html(amount);
                    $('#totlpayable').html(amount);
                },
                error: (error) => {  
                    console.log(error);
                }
            })  
        }
 
    $('#shipping_charge').change(function(){
        totalCalculation();
    });
    $('#discount_amount').change(function(){
        totalCalculation();
    });
    $('#applicableTax').change(function(){
        totalCalculation();
    });
 
}); 
</script>  