$(document).ready(function(){
    function getPosReport(){
        var date      = $('#date').val();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url: $('#pos_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                date:date, 
                branch_id: branch_id,
            },
            success: (response) => {    
               $('#pos_report_content').html(response);
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    } 
    getPosReport();  

    $( '#date').on('change',function(){
        getPosReport(); 
    });
    $('#branch_id').on('change',function(){ 
        getPosReport(); 
    }); 
});