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
