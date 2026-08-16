$(document).ready(function(){ 
    function totalSales(lifetime=null){
        var url       = $('#sales_date').data('url');
        var date      = $('#sales_date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json', 
            data:{  
                branch_id: branch_id,
                date:date,
                lifetime:lifetime
            },
            success: (response) => {  
                var data = response.data;
                //sales
                $('.total_sales').html(data.total_sales);
                $('.total_sale_payments').html(data.total_payment);
                $('.total_sale_due').html(data.total_due);
                $('.total_sale_net').html(data.total_net);  
            },
            error: (error) => {    
                console.log(error);
            }
        })   
    }
    function totalPos(lifetime=null){ 
        var url       = $('#pos_date').data('url');
        var date      = $('#pos_date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json', 
            data:{  
                branch_id: branch_id,
                date:date,
                lifetime:lifetime
            },
            success: (response) => {  
                var data = response.data; 
                //pos
                $('.total_pos').html(data.total_pos);
                $('.total_pos_payments').html(data.total_pos_payments);
                $('.total_pos_due').html(data.total_pos_due);  
                $('.total_expense').html(data.total_expense);
                 
            },
            error: (error) => {    
                console.log(error);
            }
        })   
    } 

    function totalPurchase(lifetime=null){ 
        var url       = $('#purchase_date').data('url');
        var date      = $('#purchase_date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json', 
            data:{  
                branch_id: branch_id,
                date:date,
                lifetime:lifetime
            },
            success: (response) => {  
                var data = response.data; 
                //purchase 
                $('.total_purchase_items').html(data.total_purchase_items);
                $('.total_purchase').html(data.total_purchase);
                $('.total_purchase_payments').html(data.total_purchase_payments);
                $('.total_purchase_due').html(data.total_purchase_due);  

            },
            error: (error) => {    
                console.log(error);
            }
        })   
    } 
    function totalPurchaseReturn(lifetime=null){ 
        var url       = $('#purchase_return_date').data('url');
        var date      = $('#purchase_return_date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json', 
            data:{  
                branch_id: branch_id,
                date:date,
                lifetime:lifetime
            },
            success: (response) => {  
                var data = response.data;
                //purchase_return
                $('.total_purchase_return_items').html(data.total_purchase_return_items);
                $('.total_purchase_return').html(data.total_purchase_return);
                $('.total_purchase_return_payments').html(data.total_purchase_return_payments);
                $('.total_purchase_return_due').html(data.total_purchase_return_due);  

            },
            error: (error) => {    
                console.log(error);
            }
        })   
    } 


    totalSales();
    totalPos();
    totalPurchase();
    totalPurchaseReturn();
 
   //total sales 
    $('.sales-date').on('click',function(e){
        e.preventDefault();
        $('.sales-date').removeClass('active');
        $(this).addClass('active');

        $('#sales_date').val($(this).data('date'));
        $('.salesDateBtn').html($(this).data('report')); 
        var lifetime = null;
        if($(this).data('lifetime') == 'lifetime'){
            lifetime = 'lifetime';
        } 
       totalSales(lifetime); 
    }); 
    
    //total pos
    $('.pos-date').on('click',function(e){ 
        e.preventDefault();
        $('.pos-date').removeClass('active');
        $(this).addClass('active');
        
        $('#pos_date').val($(this).data('date'));
        $('.posDateBtn').html($(this).data('report')); 
        var lifetime = null;
        if($(this).data('lifetime') == 'lifetime'){
            lifetime = 'lifetime';
        }  
        totalPos(lifetime); 
    }); 

    $('.purchase-date').on('click',function(e){
        e.preventDefault();
        $('.purchase-date').removeClass('active');
        $(this).addClass('active');
        
        $('#purchase_date').val($(this).data('date'));
        $('.purchaseDateBtn').html($(this).data('report')); 
        var lifetime = null;
        if($(this).data('lifetime') == 'lifetime'){
            lifetime = 'lifetime';
        }

        totalPurchase(lifetime); 
    }); 

    $('.purchase-return-date').on('click',function(e){
        e.preventDefault();
        $('.purchase-return-date').removeClass('active');
        $(this).addClass('active');
        
        $('#purchase_return_date').val($(this).data('date'));
        $('.purchaseReturnDateBtn').html($(this).data('report')); 
        var lifetime = null;
        if($(this).data('lifetime') == 'lifetime'){
            lifetime = 'lifetime';
        } 
        totalPurchaseReturn(lifetime); 
    }); 
    
    $('#branch_id').on('change',function(){
        totalSales();
        totalPos();
        totalPurchase();
        totalPurchaseReturn();
    });



    function recentSales(){
        var url       = $('#recent_sales').data('url'); 
        var branch_id = $('#recent_sales_branch_id').val();
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'html', 
            data:{  
                branch_id: branch_id, 
            },
            success: (response) => {  
                $('#recent_sales').html(response);
            },
            error: (error) => {    
                console.log(error);
            }
        })   
    }

    recentSales();
    $('#recent_sales_branch_id').on('change',function(){
        recentSales();
    });


    function recentPos(){
        var url       = $('#recent_pos').data('url'); 
        var branch_id = $('#recent_pos_branch_id').val();
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'html', 
            data:{  
                branch_id: branch_id, 
            },
            success: (response) => {  
                $('#recent_pos').html(response);
            },
            error: (error) => {    
                console.log(error);
            }
        })   
    }

    recentPos();
    $('#recent_pos_branch_id').on('change',function(){
        recentPos();
    });
 
});