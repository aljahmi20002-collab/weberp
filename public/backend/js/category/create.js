"use strict";
$(document).ready(function () {
    $('#business_id').on('change',function(){ 
            $.ajax({
                url: $(this).data('url'),
                type: "get",
                dataType: 'json',
                data: {"business_id":$(this).val()},
                dataType:"html",
                success: function (data) { 
                    $("#parentCategories").html(data);
                }
            });
    });


    $("#parentCategory").click(function(){
        if($('#parentDiv').hasClass('d-none')){
            $('#parentDiv').addClass('d-block');
            $('#parentDiv').removeClass('d-none');
        }else{
            $('#parentDiv').removeClass('d-block');
            $('#parentDiv').addClass('d-none');
        }
    });
});
