$(document).ready(function(){
    $('#mail_driver').change(function(){
        var mail_driver = $(this).val();
        if(mail_driver == 'smtp'){
            $('.smtp').show();
            $('.sendmail').hide();

        }
        else if(mail_driver == 'sendmail'){
            $('.sendmail').show();
            $('.smtp').hide();
        }else{
            $('.sendmail').hide();
            $('.smtp').hide();
        }
    });
})
