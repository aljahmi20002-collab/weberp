
function capitalize(str) {
    strVal = '';
    str = str.split(' ');
    for (var chr = 0; chr < str.length; chr++) {
        strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ''
    }
    return strVal
}

$('#model_name').on('change', function(){
    $(this).val(capitalize($(this).val().replace(/\s+/g,"")));

    $('#table_name').attr('value',$(this).val().toLowerCase()+'s');
});

$('#model_name').focusout(function(){
    if($(this).val() == ''){
        $('#table_name').attr('value','');
    }
});

