"use strict";
$(document).ready(function () {
    $('#employee_id').on('change',function(){
            $.ajax({
                url: $(this).data('url'),
                type: "POST",
                dataType: 'json',
                data: {"employee_id":$(this).val()},
                dataType:"html",
                success: function (data) {
                    $("#leave_assign_id").html(data);
                }
            });
    });

});
