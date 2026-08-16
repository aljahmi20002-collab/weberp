$(document).ready(function() {

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

})
