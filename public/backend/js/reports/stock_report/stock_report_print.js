"use strict";

function printDiv(divID,maincss,customcss,customcsss) {
    $(divID+' .table-responsive').removeClass('table-responsive');
    var divElements = document.getElementById(divID).innerHTML;
    var mywindow = window.open();
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write("<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css\" type=\"text/css\" />");
     var maincsshtml  = '<link rel=\"stylesheet\" href=\"'+maincss+'\" type=\"text/css\" />';
     var customcsshtml  = '<link rel=\"stylesheet\" href=\"'+customcss+'\" type=\"text/css\" />';
     var customcssshtml  = '<link rel=\"stylesheet\" href=\"'+customcsss+'\" type=\"text/css\" />';
    mywindow.document.write(maincsshtml);
    mywindow.document.write(customcssshtml);
    mywindow.document.write(customcsshtml);
    mywindow.document.write('</head><body >');
    mywindow.document.write(divElements);
    mywindow.document.write('</body></html>'); 
    setTimeout(() => {
        mywindow.print(); 
    }, 500);
}
