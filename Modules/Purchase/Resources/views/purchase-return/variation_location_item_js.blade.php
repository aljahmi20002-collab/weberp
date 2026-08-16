<script type="text/javascript">
    $(document).ready(function(){ 
            function sellingPriceCalculation(purchese_price,mergin){ 
                var profit = (parseFloat(purchese_price)/100) * parseFloat(mergin);
                var selling_price =(parseFloat(purchese_price)+ parseFloat(profit));
                var price = parseFloat(selling_price).toFixed(2);   
                if(isNaN(price)){
                    price = 0;
                }
                $('#selling_price_{{ $randomNumber }}').val(price);
            }
            function totalunitcost(purchase_price,quantity,total_unit_cost){
                var total_unitcost = purchase_price*quantity;
                total_unit_cost.html(total_unitcost.toFixed(2));
            }
            $('#quantity_{{$randomNumber}}').on('keyup',function(){
                var quantity  = $(this).val();
                var purchese_price = $('#unit_price_{{ $randomNumber }}').val();
                totalunitcost(purchese_price,quantity,$('#total_unit_cost_{{$randomNumber}}'));
                totalPurchaseCost();
            });
            $('#unit_price_{{ $randomNumber }}').on('keyup',function(){  
                var purchese_price  = $(this).val();
                var mergin          = $('#profit_percent_{{ $randomNumber }}').val();
                if(purchese_price ===''){
                    purchese_price  = 0;
                }
                if(mergin ===''){
                    mergin  = 0;
                }
                sellingPriceCalculation(purchese_price,mergin);
                var quantity = $('#quantity_{{$randomNumber}}').val();
                totalunitcost(purchese_price,quantity,$('#total_unit_cost_{{$randomNumber}}'));

                totalPurchaseCost();
            });

            $('#profit_percent_{{ $randomNumber }}').on('change',function(){ 
                var purchese_price  = $('#unit_price_{{ $randomNumber }}').val();
                var mergin          = $(this).val();
                if(purchese_price ===''){
                    purchese_price  = 0;
                }
                if(mergin ===''){
                    mergin  = 0;
                }
                sellingPriceCalculation(purchese_price,mergin);
            });
            $('#selling_price_{{ $randomNumber }}').on('change',function(){ 
                var purchese_price  = $('#unit_price_{{ $randomNumber }}').val();
                var mergin          = $('#profit_percent_{{ $randomNumber }}').val();
                if(purchese_price ===''){
                    purchese_price  = 0;
                }
                if(mergin ===''){
                    mergin  = 0;
                }
                sellingPriceCalculation(purchese_price,mergin);
            });
            function totalPurchaseCost(){
                var totalPurchaseCost = 0; 
                $('.totalUnitCost').each(function(index){ 
                    totalPurchaseCost += parseInt($(this).text());
                }); 
                var p = totalPurchaseCost.toFixed(2);
                $('#total_purchase_costs').html(p);

                var quantity          = 0;
                $('.quantity').each(function(index){ 
                    quantity += parseInt($(this).val());
                });
                $('#total_item').html(quantity);

                totalpurchasePriceWithTax();
            }
            
            totalPurchaseCost();
            $('.purchase_variation_location_remove').click(function(){ 
                $(this).parent().parent().remove();
                totalPurchaseCost();
            });


            function totalpurchasePriceWithTax(){ 
                var amount = 0;
                $.ajax({
                    url: $('#applicableTax').data('url'),
                    method: 'post',
                    dataType: 'html',
                    data:{  
                        tax_id:$('#applicableTax').val()
                    },
                    success: (response) => { 
                        var taxPercent = parseInt(response);
                        var totalPurchaseCost = parseInt($('#total_purchase_costs').text());
                        var taxAmount     = (totalPurchaseCost/100) * taxPercent;
                        var totalPurchaseCostWithTax = totalPurchaseCost + taxAmount;
                        var amount = totalPurchaseCostWithTax.toFixed(2);
                        var txamount = taxAmount.toFixed(2);
                        $('#totlPurchaseTax').html(txamount);
                        $('#totlPurchasePriceWithTax').html(amount);

                        $('#total_price').val(totalPurchaseCost);
                        $('#total_tax_amount').val(txamount);
                        $('#total_buy_cost').val(amount);

                    },
                    error: (error) => {  
                        console.log(error);
                    }
                })  
            }

    });
</script>