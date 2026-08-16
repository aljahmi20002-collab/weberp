$(document).ready(function(){
      function optionClose (){
        $('.optionClose').click(function(){
            $(this).parent().remove();
        });
    }
    optionClose();

    $('.addOption').click(function(){
        var item = '';
        item += '<div class="d-flex option-item mt-3">';
        item += '<input type="text" name="values[]" class="form-control form--control" />';
        item += '<label class="py-2 px-3 optionClose"  ><i class="fa fa-trash"></i></label>';
        item += '</div>';
        $('.options').append(item);
        optionClose();
    });
});