$(document).ready(function(){
    $('#to_branch').change(function(){ 
        var url  = $(this).data('url'); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html', 
            data:{ 
                branch_id: $(this).val(), 
            },
            success: (response) => { 
                $('#to_account').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }); 
});