$(document).ready(function(){
    function stockReportFetch(){ 
        var branch_id       = $('#branch_id').val(); 
        var category_id     = $('#category_id').val(); 
        var subcategory_id  = $('#subcategory_id').val(); 
        var brand_id        = $('#brand_id').val(); 
        var unit_id         = $('#unit_id').val(); 
        $.ajax({
            url: $('#stock_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                branch_id: branch_id,
                category_id:category_id,
                subcategory_id:subcategory_id,
                brand_id:brand_id,
                unit_id:unit_id
            },
            success: (response) => {  
                var totalcalculation = JSON.parse(response); 
                var totaldata        = totalcalculation.total_calculation.original;
                $('.total_stock_selling_price').html(totaldata.total_current_stock_selling_price);
                $('.total_stock_purchase_price').html(totaldata.total_current_stock_purchase_price);
                $('.total_stock_gross_profit').html(totaldata.total_current_stock_gross_profit);
                $('.total_stock_tax_amount').html(totaldata.total_current_stock_tax);
                $('#stock_report_content').html(totalcalculation.view);  
                $('.branch_name').html(totalcalculation.branch); 
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    }
    stockReportFetch(); 
 
    $('#branch_id').on('change',function(){ 
        stockReportFetch();
    });

 
   $('#category_id').on('change',function(){
       stockReportFetch();
       var url         = $(this).data('url'); 
       var category_id = $(this).val();  
       $.ajax({
           url: url,
           method: 'post',
           dataType: 'html',
           data:{  
               category_id:category_id
           },
           success: (response) => { 
               $('#subcategory_id').html(response);
           },
           error: (error) => {  
               console.log(error);
           }
       }) 
   });

   $('#subcategory_id').on('change',function(){  
       stockReportFetch();  
   });
   $('#brand_id').on('change',function(){ 
        stockReportFetch();
    });
    
   $('#unit_id').on('change',function(){  
        stockReportFetch();
    });
    
});