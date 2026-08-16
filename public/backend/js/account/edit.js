$(document).ready(function(){
    $('#payment_gateway').change(function(){
        if($(this).val() == $('#cash_enum').val()){
            $('.bank').each(function(index){
                  $(this).removeClass('d-block');
                  $(this).removeClass('d-none');
                  $(this).addClass('d-none');
            });
            $('.mobile').each(function(index){
                  $(this).removeClass('d-block');
                  $(this).removeClass('d-none');
                  $(this).addClass('d-none');
            });

            $('.both').each(function(index){
                  $(this).removeClass('d-block');
                  $(this).removeClass('d-none');
                  $(this).addClass('d-none');
            });
 
        }else if($(this).val() == $('#bank_enum').val()){
            $('.bank').each(function(index){
                $(this).removeClass('d-block');
                $(this).removeClass('d-none');
                $(this).addClass('d-block');
            });
            $('.mobile').each(function(index){
                $(this).removeClass('d-block');
                $(this).removeClass('d-none');
                $(this).addClass('d-none');
            });

            $('.both').each(function(index){
                $(this).removeClass('d-block');
                $(this).removeClass('d-none');
                $(this).addClass('d-block');
          });
           
        }else if($(this).val() == $('#mobile_enum').val()){
            $('.bank').each(function(index){
                $(this).removeClass('d-block');
                $(this).removeClass('d-none');
                $(this).addClass('d-none');
            });
            $('.mobile').each(function(index){
                $(this).removeClass('d-block');
                $(this).removeClass('d-none');
                $(this).addClass('d-block');
            });

            $('.both').each(function(index){
                $(this).removeClass('d-block');
                $(this).removeClass('d-none');
                $(this).addClass('d-block');
            });
        }
    });
});