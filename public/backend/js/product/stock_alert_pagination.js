$(document).ready(function(){ 
    function searchStockAlert(page=null){
        var url         = $('#stock-alert-search').data('url'); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                search: $('#stock-alert-search').val(), 
                page: page
            },
            success: (response) => {   
                $('#stock_alert_content').html(response); 
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
        searchStockAlert(page);
    }); 
});