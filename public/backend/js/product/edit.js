"use strict";
$(document).ready(function(){

    function units(){
        var url         = $('#unit_id').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('#unit_id').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }

    function categories(){
        var url         = $('#category_id').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('#category_id').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }
    function subcategories(){
        var url         = $('#subcategory').data('url');
        var business_id = $('#business_id').val();
        var category_id = $('#category_id').val();  
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id,
                category_id:category_id,
            },
            success: (response) => { 
                $('#subcategory').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }

    function branches(){
        var url         = $('#business_id').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('#branch').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }
    function applicableTax(){
        var url         = $('#applicableTax').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('#applicableTax').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }

    function brands(){
        var url         = $('#brand_id').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('#brand_id').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }
  
 
    function variations(){
        var url         = $('#getvariation_input').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('.variation_id').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }
  
    function defaultmergin(){
        var url         = $('#single_mergin_url').data('url');
        var business_id = $('#business_id').val(); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                business_id: business_id, 
            },
            success: (response) => { 
                $('#single_mergin').val(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }
    
    $('#business_id').on('change',function(){
        units();
        categories();
        subcategories();
        branches();
        applicableTax();
        brands(); 
        variations();
        defaultmergin();

    });

    $('#category_id').on('change',function(){
        subcategories();
    });


    $('#manage_stock').click(function(){
         $('.alert_quantity').toggle();
    }); 

    function variationValues(thises){   
        $.ajax({
            url: $(thises).data('url'),
            method: 'post',
            dataType: 'html',
            data:{  
                id:$(thises).val(), 
            },
            success: (response) => { 
                $('#variation_values').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })  

    }

    $('#variation_id').change(function(){
        var thises = $(this);
        variationValues(thises);
    });

  function sellingPriceCalculation(purchese_price,mergin){ 
    console.log(purchese_price,mergin);
        var profit = (parseFloat(purchese_price)/100) * parseFloat(mergin);
        var selling_price =(parseFloat(purchese_price)+ parseFloat(profit));
        var price = parseFloat(selling_price).toFixed(2);   
        $('#selling_price').val(price);
  }

    $('#purchese_price').on('keyup',function(){ 
        var purchese_price  = $(this).val();
        var mergin          = $('#profit_percent').val();
        if(purchese_price ===''){
            purchese_price  = 0;
        }
        if(mergin ===''){
            mergin  = 0;
        }
        sellingPriceCalculation(purchese_price,mergin);
    });
 
    $('#profit_percent').on('change',function(){ 
        var purchese_price  = $('#purchese_price').val();
        var mergin          = $(this).val();
        if(purchese_price ===''){
            purchese_price  = 0;
        }
        if(mergin ===''){
            mergin  = 0;
        }
        sellingPriceCalculation(purchese_price,mergin);
    });


    $('#selling_price').on('change',function(){ 
        var purchese_price  = $('#purchese_price').val();
        var mergin          = $('#profit_percent').val();
        if(purchese_price ===''){
            purchese_price  = 0;
        }
        if(mergin ===''){
            mergin  = 0;
        }
        sellingPriceCalculation(purchese_price,mergin);
    });

   
});