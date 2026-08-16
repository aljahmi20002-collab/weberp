
$(document).ready(function(){


    // Delete confirmation
    // start
  
    $(document).on('submit', 'form#delete', function(e){
        var title  = $(this).data('title');
        var yes    = $('#delete').data('yes');
        var cancel = $('#delete').data('cancel');
        e.preventDefault();
        var form = this;

        Swal.fire({
        icon: "warning", 
        title: title, 
        customClass:{
            container:'swal-delete-alert',
            confirmButton:'btn btn-primary',
            cancelButton:'btn btn-danger'
        },
        position: 'center',
        width: 300,
        showOkButton: true,
        showCancelButton: true,
        confirmButtonText: yes,
        cancelButtonText: cancel,
        }).then((result) => {
            if (result.isConfirmed){
                form.submit();
            }
        })
    });
    

    // end

    var fullyear = new Date().getFullYear()+1;
    var year = '1901:'+fullyear;
    $('.date').datepicker({
        changeMonth:true,
        dateFormat: 'dd/mm/yy',
        yearRange: year,
        changeYear:true,
    });


    $('textarea.form-control').summernote({
        placeholder: 'Enter Description',
        tabsize: 2,
        height: 400
    });
 
    $('.select2').select2();

    $('[data-toggle="tooltip"]').tooltip({
      placement:'right'
    })
  
});


