$(document).ready(function() {

    var prevlogoImg = $('.logo .thumb').html();
    function logoPicURL(logo) {
        if (logo.files && logo.files[0]) {
            var logoUploadedFile = new FileReader();
            logoUploadedFile.onload = function (e) {
                var previewLogo = $('.logo').find('.thumb');
                previewLogo.html(`<img src="${e.target.result}" class="logo-img" alt="user">`);
                previewLogo.addClass('image-loaded');
                previewLogo.hide();
                previewLogo.fadeIn(650);
                $(".logo .image-view").hide();
                $(".logo .remove-thumb").show();
            }
            logoUploadedFile.readAsDataURL(logo.files[0]);
        }
    }
    $("#logo-image-upload").on('change', function () {
        logoPicURL(this);
    });
    $(".logo .remove-thumb").on('click', function () {
        $(".logo .thumb").html(prevlogoImg);
        $(".logo .thumb").removeClass('image-loaded');
        $(".logo .image-view").show();
        $(this).hide();
    })




    var prevImg = $('.pupload .thumb').html();
    function proPicURL(input) {
        if (input.files && input.files[0]) {
            var uploadedFile = new FileReader();
            uploadedFile.onload = function (e) {
                var preview = $('.pupload').find('.thumb');
                preview.html(`<img src="${e.target.result}" class="pu-img" alt="user">`);
                preview.addClass('image-loaded');
                preview.hide();
                preview.fadeIn(650);
                $(".image-view").hide();
                $(".remove-thumb").show();
            }
            uploadedFile.readAsDataURL(input.files[0]);
        }
    }
    $("#profile-image-upload").on('change', function () {
        proPicURL(this);
    });
    $(".remove-thumb").on('click', function () {
        $(".pupload .thumb").html(prevImg);
        $(".pupload .thumb").removeClass('image-loaded');
        $(".image-view").show();
        $(this).hide();
    })


    //table empty image
    
    var prevtable_empty_imageImg = $('.table_empty_image .thumb').html();
    function table_empty_imagePicURL(table_empty_image) {
        if (table_empty_image.files && table_empty_image.files[0]) {
            var table_empty_imageUploadedFile = new FileReader();
            table_empty_imageUploadedFile.onload = function (e) {
                var table_empty_imageUploadedFilepreviewLogo = $('.table_empty_image').find('.thumb');
                table_empty_imageUploadedFilepreviewLogo.html(`<img src="${e.target.result}" class="logo-img" alt="user">`);
                table_empty_imageUploadedFilepreviewLogo.addClass('image-loaded');
                table_empty_imageUploadedFilepreviewLogo.hide();
                table_empty_imageUploadedFilepreviewLogo.fadeIn(650);
                $(".table_empty_image .image-view").hide();
                $(".table_empty_image .remove-thumb").show();
            }
            table_empty_imageUploadedFile.readAsDataURL(table_empty_image.files[0]);
        }
    }
    $("#table_empty_image-image-upload").on('change', function () {
        table_empty_imagePicURL(this);
    });
    $(".table_empty_image .remove-thumb").on('click', function () {
        $(".table_empty_image .thumb").html(prevtable_empty_imageImg);
        $(".table_empty_image .thumb").removeClass('image-loaded');
        $(".table_empty_image .image-view").show();
        $(this).hide();
    })


    // table_search_image

    var prevTableSearch = $('.table_search_image .thumb').html();
    function searchImage(table_search_image) {
        if (table_search_image.files && table_search_image.files[0]) {
            var table_search_imageUploadedFile = new FileReader();
            table_search_imageUploadedFile.onload = function (e) {
                var previewtable_search_image = $('.table_search_image').find('.thumb');
                previewtable_search_image.html(`<img src="${e.target.result}" class="table_search_image-img" alt="user">`);
                previewtable_search_image.addClass('image-loaded');
                previewtable_search_image.hide();
                previewtable_search_image.fadeIn(650);
                $(".table_search_image .image-view").hide();
                $(".table_search_image .remove-thumb").show();
            }
            table_search_imageUploadedFile.readAsDataURL(table_search_image.files[0]);
        }
    }
    $("#table_search_image-image-upload").on('change', function () {
        searchImage(this);
    });
    $(".table_search_image .remove-thumb").on('click', function () {
        $(".table_search_image .thumb").html(prevTableSearch);
        $(".table_search_image .thumb").removeClass('image-loaded');
        $(".table_search_image .image-view").show();
        $(this).hide();
    })



})
