$(document).ready(function(){
    function salesFetch(){
        var date        = $('#date').val();
        var branch_id   = $('#branch_id').val();
        var customer_id = $('#customer_id').val();
        $.ajax({
            url: $('#customer_pos_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                date:date,
                branch_id:branch_id,
                customer_id: customer_id
            },
            success: (response) => {  
                var res = JSON.parse(response); 
                $('.total_purchase').html(res.get_total.total_sales_count);
                $('.total_sale_price').html(res.get_total.total_sale_price);
                $('.total_sale_payments').html(res.get_total.total_sale_payment);
                $('.total_sale_due').html(res.get_total.total_sale_due);

                $('#customer_pos_report_content').html(res.view); 
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    }
    salesFetch(); 
 
    $('#date').on('change',function(event){ 
        salesFetch(); 
    });
    $('#customer_id').on('change',function(event){ 
        salesFetch(); 
    });
    $('#branch_id').on('change',function(event){ 
        salesFetch(); 
    });

});