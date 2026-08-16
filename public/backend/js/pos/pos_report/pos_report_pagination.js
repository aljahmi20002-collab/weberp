$(document).ready(function(){
    function getPosReport(page=null){
        var branch_id = $('#branch_id').val();
        var date      = $('#date').val();
        $.ajax({
            url: $('#pos_report_content').data('url'),
            method: 'get',
            dataType: 'html', 
            data:{  
                branch_id: branch_id,
                date:date, 
                page:page
            },
            success: (response) => { 
                $('#pos_report_content').html(response);
                window.scrollTo(0, 0);
            },
            error: (error) => {    
                console.log(error);
            }
        }) 
    } 

    $('.pagination a.page-link').on('click',function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1]; 
        getPosReport(page);
    });
    
});