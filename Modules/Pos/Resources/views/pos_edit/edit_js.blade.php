<script>
    //pos item add 
 
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
                }else{
    
                    var audio = $('#error-audio')[0];
                    if (audio !== undefined) {
                        audio.play();
                    }
                    toastr.error('Product out of stock.','Error');
                }
            }else if(response == ''){ 
                var audio = $('#error-audio')[0];
                if (audio !== undefined) {
                    audio.play();
                }
                toastr.error('Product out of stock.','Error'); 
            }
            else{ 
                //sound effect
                var audio = $('#success-audio')[0];
                if (audio !== undefined) {
                    audio.play();
                }
                //end sound effect  
                toastr.success('Product added successfully.','Success');  
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

                        $('#total_price').val(totalPurchaseCost);
                        $('#total_tax_amount').val(txamount);
                        $('#total_sell_price').val(amount);

                    },
                    error: (error) => {  
                        console.log(error);
                    }
                })  
            }
     
            totalCalculation();
        
    
     
    </script> 