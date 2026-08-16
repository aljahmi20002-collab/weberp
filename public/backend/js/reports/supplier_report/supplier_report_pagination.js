$(document).ready(function(){

    function supplierReport(page=null){
        var branch_id = $('#branch_id').val();  
        var supplier_id = $('#supplier_id').val();
        var date      = $('#date').val();
        var report_type  = $('#report_type').val();
        $.ajax({
            url: $('#supplier_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                date:date, 
                branch_id: branch_id,  
                supplier_id:supplier_id,
                report_type:report_type,
                page:page
            },
            success: (response) => {  
                $('#supplier_report_content').html(response);
                window.scrollTo(0,0);
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    } 

    $('.pagination a.page-link').on('click',function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1]; 
        supplierReport(page);
    });
    
});