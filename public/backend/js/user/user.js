$(document).ready(function () {

    //user status update : active and inactive
    $(document).on('change','.status',function (e) {
        var key=$(this).val();
        $.ajax({
            type : 'POST',
            url : $(this).data('url'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {'key':key},
            dataType : "json",
            success : function (data) { 
                toastr.success('Status updated successfully.','Success');  
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error:function(data){
                toastr.error('Something went wrong.','Error');   
                console.log(data);
            }
        });
    });


    //user account update : ban or unban
    $(document).on('change','.banunban',function (e) {
        var key=$(this).val();
        $.ajax({
            type : 'POST',
            url : $(this).data('url'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {'key':key},
            dataType : "json",
            success : function (data) { 
                toastr.success('Something went wrong.','Success');   
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error:function(data){
                toastr.error('Something went wrong.','Error');  
                console.log(data);
            }
        });
    });
    
    
});
