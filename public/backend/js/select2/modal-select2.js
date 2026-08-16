$(document).ready(function(){
    $('.modal .select2').select2();
    $('.modal .select2').each(function () {
        $(this).select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this).parent(),
        });
    });
});