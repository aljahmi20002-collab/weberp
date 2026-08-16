$(document).ready(function(){
    $('#from_branch').change(function(){ 
        var url  = $(this).data('url'); 
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html', 
            data:{ 
                branch_id: $(this).val(), 
            },
            success: (response) => { 
                $('#from_account').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    }); 
});