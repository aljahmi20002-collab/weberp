$(document).ready(function(){
    function expenseFetch(){
        var branch_id = $('#branch_id').val();
        var date      = $('#date').val();
        $.ajax({
            url: $('#expense_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                branch_id: branch_id,
                date:date
            },
            success: (response) => {  
                $('#expense_report_content').html(response); 
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    }
    expenseFetch(); 
 
    $('#date').on('change',function(event){ 
        expenseFetch(); 
    });
    $('#branch_id').on('change',function(event){ 
        expenseFetch(); 
    });

});