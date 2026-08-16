$(document).ready(function(){
    function supplierReport(){ 
        var date         = $('#date').val(); 
        var branch_id    = $('#branch_id').val();  
        var supplier_id  = $('#supplier_id').val();
        var report_type  = $('#report_type').val();
        $.ajax({
            url: $('#supplier_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                date:date,
                supplier_id:supplier_id,
                branch_id: branch_id,
                report_type:report_type
            },
            success: (response) => {  
                 $('#supplier_report_content').html(response);
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    }
    supplierReport(); 
 
    $('#branch_id').on('change',function(){ 
        supplierReport();
    });
    $('#supplier_id').on('change',function(){ 
       
        supplierReport();
    });

    $('#date').on('change',function(){ 
        supplierReport(); 
    });
    $('#report_type').on('change',function(){ 
        supplierReport(); 
    });
 
});