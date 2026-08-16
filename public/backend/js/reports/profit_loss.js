$(document).ready(function(){

    function getProfit(){
        var branch_id = $('#branch_id').val();
        var date      = $('#date').val();
        $.ajax({
            url: $('#profit_loss_url').data('url'),
            method: 'post',
            dataType: 'json', 
            data:{  
                branch_id: branch_id,
                date:date, 
            },
            success: (response) => {    
                var data = response.data;
                //sales
                $('.total_sales_price').html(data.total_sales_price); 
                $('.total_sale_tax_amount').html(data.total_sale_tax_amount);
                $('.total_sale_shipping_charge').html(data.total_sale_shipping_charge);
                $('.total_sale_discount_amount').html(data.total_sale_discount_amount);
                //pos 
                $('.total_pos_sale_price').html(data.total_pos_sale_price); 
                $('.total_pos_sale_tax_amount').html(data.total_pos_sale_tax_amount);
                $('.total_pos_sale_shipping_charge').html(data.total_pos_sale_shipping_charge);
                $('.total_pos_sale_discount_amount').html(data.total_pos_sale_discount_amount);
                //purchase 
                $('.total_purchase_cost').html(data.total_purchase_cost);
                $('.total_purchase_tax_amount').html(data.total_purchase_tax_amount); 
                //purchase return  
                $('.total_purchase_return_price').html(data.total_purchase_return_price);
                $('.total_purchase_return_tax_amount').html(data.total_purchase_return_tax_amount); 
                //stock transfer  
                $('.total_transfer_price').html(data.total_transfer_price);
                $('.total_shipping_charge').html(data.total_shipping_charge); 
                //income expense
                $('.total_income').html(data.total_income);
                $('.total_expense').html(data.total_expense); 
                //gross profit
                $('.total_gross_profit').html(data.total_gross_profit); 
                $('.total_net_profit').html(data.total_net_profit); 

                //filter information 
                $('.filter_date_div').html(date); 
                $('.filter_branch_div').html(data.branch); 

            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    }
     
    getProfit(); 
    $('.filter_date_div').html($('#date').val()); 

    $( '#date').on('change',function(){
        getProfit(); 
    });
    $('#branch_id').on('change',function(){ 
        getProfit(); 
    }); 
});