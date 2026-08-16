$(document).ready(function(){
            
            $('#applicableTax').change(function(){ 
                $.ajax({
                    url: $(this).data('url'),
                    method: 'post',
                    dataType: 'html',
                    data:{  
                        tax_id:$(this).val()
                    },
                    success: (response) => { 
                        totalCalculation(response);
                    },
                    error: (error) => {  
                        console.log(error);
                    }
                })
        });

  
        $.ajax({
            url: $('#applicableTax').data('url'),
            method: 'post',
            dataType: 'html',
            data:{  
                tax_id:$('#applicableTax').val()
            },
            success: (response) => { 
                totalCalculation(response);
            },
            error: (error) => {  
                console.log(error);
            }
        }) 
 
        function totalCalculation(response){
            var amount = 0;
            var shippingcharge  = parseInt($('#shipping_charge').val());
            var discountamount  = parseInt($('#discount_amount').val());
            if(isNaN(shippingcharge)){
                shippingcharge  = 0;
            }
            if(isNaN(discountamount)){
                discountamount  = 0;
            }

            $('#total_shipping_charge').html(shippingcharge);
            $('#total_discount_amount').html(discountamount)
            
            var taxPercent        = parseInt(response);
            var totalPurchaseCost = parseInt($('#total_purchase_costs').text());
            var taxAmount         = (totalPurchaseCost/100) * taxPercent;
            var totalPurchaseCostWithTax = (totalPurchaseCost + taxAmount + shippingcharge) - discountamount;
            var amount            = totalPurchaseCostWithTax.toFixed(2);
            var txamount          = taxAmount.toFixed(2);
            $('#totlPurchaseTax').html(txamount);
            $('#totlPurchasePriceWithTax').html(amount);
            
            $('#total_price').val(totalPurchaseCost);
            $('#total_tax_amount').val(txamount);
            $('#total_sell_price').val(amount);
        }

});