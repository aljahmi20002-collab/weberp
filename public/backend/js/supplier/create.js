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
                    $("#branch_id").html(data);
                }
            });
    });
});
