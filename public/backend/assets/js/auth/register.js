$(document).ready(function(){
    var fullyear = new Date().getFullYear()+1;
    var year = '1901:'+fullyear;
    $('.dateofbirth').datepicker({
        changeMonth:true,
        dateFormat: 'dd/mm/yy',
        yearRange: year,
        changeYear:true,
    });
});

$('#signUp').prop('disabled', true);
$('#remember').change(function(){
    if($(this).is(':checked')){
        $('#signUp').prop('disabled', false);
    }else{
        $('#signUp').prop('disabled', true);
    }
});
