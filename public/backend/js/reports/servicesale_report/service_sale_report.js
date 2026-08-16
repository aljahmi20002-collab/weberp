$(document).ready(function(){
    function getSaleReport(){
        var date      = $('#date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: $('#service_sale_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                date:date, 
                branch_id: branch_id,
            },
            success: (response) => {    
               $('#service_sale_report_content').html(response);
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    } 
    getSaleReport();  

    $( '#date').on('change',function(){
        getSaleReport(); 
    });
    $('#branch_id').on('change',function(){ 
        getSaleReport(); 
    }); 
});