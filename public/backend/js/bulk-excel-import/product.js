"use strict";
$(document).ready(function(){
   
    $('#category_id').on('change',function(){
        var url         = $(this).data('url'); 
        var category_id = $(this).val();  
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'html',
            data:{  
                category_id:category_id,
            },
            success: (response) => { 
                $('#subcategory').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    });

  

    $('#variation_id').change(function(){
        $.ajax({
            url: $(this).data('url'),
            method: 'post',
            dataType: 'html',
            data:{  
                id:$(this).val(), 
            },
            success: (response) => { 
                $('#variation_values').html(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })  
    });

 

   
});