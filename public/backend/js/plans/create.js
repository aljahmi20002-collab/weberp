$(document).ready(function(){
     
    $(document).on('change', '#selectAllModules', function(){ 
        if ($(this).is(':checked')) { 
            $('.check-module').find('.common-key').prop('checked', true);
        }else{
            $('.check-module').find('.common-key').prop('checked', false);
        }
    }); 

});