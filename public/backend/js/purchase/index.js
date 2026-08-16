$(document).ready(function(){
    $('#purchase-search').keyup(function(){
       var loader = '<tr class="loader-row">';
        loader += '<td colspan="13" >';
        loader +='<div class="loader table-loader"></div>';
        loader += '</td>';
        loader += '</tr>';
        $('#purchase_body').html(loader);
        var url         = $(this).data('url'); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{ 
                search: $(this).val(), 
            },
            success: (response) => { 
                $('#purchase_body').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    });
 
});