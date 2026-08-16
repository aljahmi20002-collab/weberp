$(document).ready(function(){

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
        withTaxTotalCalculation();
    }
    totalCalculation();

    function withTaxTotalCalculation(){
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

    withTaxTotalCalculation();

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