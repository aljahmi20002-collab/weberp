$(document).ready(function(){

    function getPurchaseReport(){
        var date      = $('#date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: $('#purchase_return_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                date:date, 
                branch_id: branch_id,
            },
            success: (response) => {    
               $('#purchase_return_report_content').html(response);
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    } 
    getPurchaseReport();  

    $( '#date').on('change',function(){
        getPurchaseReport(); 
    });
    $('#branch_id').on('change',function(){ 
        getPurchaseReport(); 
    }); 
});