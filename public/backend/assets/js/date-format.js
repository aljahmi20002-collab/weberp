$(document).ready(function(){
    var fullyear = new Date().getFullYear()+1;
    var year = '1901:'+fullyear;
    $('.dateformat2').datepicker({
            changeMonth:true,
            dateFormat: 'dd-mm-yy',
            yearRange: year,
            changeYear:true,
        });


   
});
