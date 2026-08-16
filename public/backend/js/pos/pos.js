'use strict';
$(document).ready(function(){
    $('.load-circle .table-loader').hide();
    $('.notfound').hide(); 
 
    $('.pos-items').scroll(function(){ 
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            var page = parseInt($('#suggetion_page').val());
            page = page+1; 
            $('#suggetion_page').val(page);
            var branch_id   = $('#branch_id').val();
            var category_id = $('#category_id').val();
            var brand_id    = $('#brand_id').val();
            var page        = page;
            get_product_suggestion_list(category_id,brand_id,branch_id,page);
        }
    });


    function get_product_suggestion_list(category_id,brand_id,branch_id,page){
    
        $.ajax({
            url: $('.pos-item-content').data('url'),
            method: 'get',
            dataType: 'html',
            beforeSend: function(){ 
                    $('.load-circle .table-loader').show();  
                    $('.notfound').hide(); 
            },
            data:{  
                branch_id: branch_id,
                category_id:category_id,
                brand_id:brand_id,
                page:page
            },
            success: (response) => {    
                if(response == ''){   
                    $('.load-circle .table-loader').hide();  
                    $('.notfound').show(); 
                }else{ 
                    $('.pos-item-content').append(response);   
                }
            },
            error: (error) => {   
                $('.load-circle .table-loader').show();  
                $('.notfound').show();
            }
        }) 
    }

    $('#category_id').change(function(){
        $('#suggetion_page').val(0);
        var page = parseInt($('#suggetion_page').val());
        page = page+1; 
        $('#suggetion_page').val(page);
        var branch_id   = $('#branch_id').val();
        var category_id = $('#category_id').val();
        var brand_id    = $('#brand_id').val(); 
        get_filter_product_suggestion_list(category_id,brand_id,branch_id,page);
    });

    $('#brand_id').change(function(){ 
        $('#suggetion_page').val(0);
        var page = parseInt($('#suggetion_page').val());
        page = page+1; 
        $('#suggetion_page').val(page);
        var branch_id   = $('#branch_id').val();
        var category_id = $('#category_id').val();
        var brand_id    = $('#brand_id').val(); 
        get_filter_product_suggestion_list(category_id,brand_id,branch_id,page);
    });


    $('#branch_id').change(function(){ 
        $('#suggetion_page').val(0);
        var page = parseInt($('#suggetion_page').val());
        page = page+1; 
        $('#suggetion_page').val(page);
        var branch_id   = $('#branch_id').val();
        var category_id = $('#category_id').val();
        var brand_id    = $('#brand_id').val(); 
        get_filter_product_suggestion_list(category_id,brand_id,branch_id,page);
    });

    function get_filter_product_suggestion_list(category_id,brand_id,branch_id,page){
        $.ajax({
            url: $('#filter_product_fetch_url').data('url'),
            method: 'get',
            dataType: 'html',
            beforeSend: function(){ 
                    $('.load-circle .table-loader').show();  
                    $('.notfound').hide();
            },
            data:{  
                branch_id: branch_id,
                category_id:category_id,
                brand_id:brand_id,
                page:page
            },
            success: (response) => {    
                if(response == ''){   
                    $('.pos-item-content').html('');  
                    $('.load-circle .table-loader').hide();  
                    $('.notfound').show(); 
                }else{  
                    $('.pos-item-content').html('');  
                    $('.pos-item-content').append(response);  
                    $('.load-circle .table-loader').hide(); 
                }
            },
            error: (error) => {   
                $('.notfound').show();
            }
        }) 
    }
 
    $.ajax({
        url: $('.pos-item-content').data('url'),
        method: 'get',
        dataType: 'html',
        data:{  
            branch_id: $('#branch_id').val(),
            category_id:$('#category_id').val(),
            brand_id:$('#brand_id').val()
        }
    })
    .done(function(response){
        $('.pos-item-content').append(response); 
        if(response == ''){
            $('.notfound').show();
        }
    })  
    //pos item add 
  
    $('form').on('submit', function (e) { 
        e.preventDefault();
        var form = this;
        var count = 0;
        $('.variation_locations_array').each(function(){
            count += 1;
        }); 
        if(count >0){
            form.submit();
        }else{
            var audio = $('#error-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
            toastr.error('No product added. some product add first.','Error');  
        }
 
    });



 
    //------------product search  
    function productFind(){ 
        $('#product_find').val('');
    }  
    $('#product_find').autocomplete({
        minLength:1,
        source: function(request,add){//source 
            var products =[];
            const ids = $('input[name="variation_item_unique_add[]"]').map(function () {
                return this.value;  
            }).get(); 
            $.ajax({
                url: $('#product_find').data('url'),
                method: 'post',
                dataType: 'json',
                async:false,
                data:{  
                    search: $('#product_find').val(),
                    branch_id: $('#branch_id').val(),
                },
                success: (response) => {  
                    if(response.single){
                        productFind(); 
                        if(ids.includes(response.id.toString()) == true){  
                            var audio = $('#error-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            toastr.error('Product Already added.','Error');   
                        }else{
                            //sound effect
                            var audio = $('#success-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            //end sound effect 

                            $('#pos_item_content').append(response.view);
                        } 
                    }else{   
                        if(response.length <=0){ 
                            var audio = $('#error-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            toastr.error('Product was not found.','Error');  
                        } 
                        products = $.map(response, function(item){
                            return { 
                                value:'',
                                item_id:item.value,
                                label:item.label,
                                qty_available: item.qty_available
                            };
                        });
                    }
                },
                error: (error) => {  
                    var audio = $('#error-audio')[0];
                    if (audio !== undefined) {
                        audio.play();
                    }
                    toastr.error('Something went wrong.','Error');  
                    console.log(error);
                }
            })   
            add(products); 
        }, 
        focus: function(event, ui) { 
            if (parseInt(ui.item.qty_available) <= 0) {
                return false;
            }
        },
        select:function(e,ui){
            const ids = $('input[name="variation_item_unique_add[]"]').map(function () {
                return this.value;  
            }).get(); 
            $.ajax({
                url: $('#variation_location_url').data('url'),
                method: 'post',
                dataType: 'html',
                data:{  
                    variation_location_id:ui.item.item_id
                },
                success: (response) => { 
                    productFind();   
                    if(ids.includes(ui.item.item_id.toString()) == true){  
                        var totalquantity    = parseInt($('.qty_available'+ui.item.item_id).text());
                        var currentQuantity  = parseInt($('.qty'+ui.item.item_id).val());
                         if(isNaN(currentQuantity)){
                            currentQuantity = 0;
                         }
                        if(totalquantity > currentQuantity){
                            //sound effect
                            var audio = $('#success-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            //end sound effect  
                            toastr.success('Product added successfully.','Success');  
                            $('.qty'+ui.item.item_id).val(currentQuantity+1);
                        }else{ 
                            //sound effect
                            var audio = $('#error-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            //end sound effect  
                            toastr.error('Product out of stock.','Error');  
                        }
                    }else{
                        //sound effect
                        var audio = $('#success-audio')[0];
                        if (audio !== undefined) {
                            audio.play();
                        }
                        //end sound effect  
                        toastr.success('Product added successfully.','Success');  
                        $('#pos_item_content').append(response);
                    }
                    
                   
                },
                error: (error) => {  
                    console.log(error);
                }
            })
        },
        autoFocus: true,
       delay: 500
      })
        .autocomplete('instance')._renderItem = function(ul, item) { 
        if ( item.qty_available <= 0 ) {
            var string = '<li class="ui-state-disabled"><div>' + item.label+' (Out of stock)</div></li>'; 
            return $(string).appendTo(ul);
        } else {
            var string = '<div>' + item.label+'</div>'; 
            return $('<li>').append(string).appendTo(ul);
        }
    };

 //------------end product search 

    $('#customer').on('change',function(){ 

        if($(this).val() == 'walk_customer'){
            $('.walk_customer_phone').removeClass('d-none');
            $('.walk_customer_phone').addClass('d-block');

            $('.exist_customer').removeClass('d-block');
            $('.exist_customer').addClass('d-none');

        }else{
            $('.walk_customer_phone').removeClass('d-block');
            $('.walk_customer_phone').addClass('d-none');

            $('.exist_customer').removeClass('d-none');
            $('.exist_customer').addClass('d-block');

            $.ajax({
                url: $(this).data('url'),
                method: 'get',
                dataType: 'json',
                async:false,
                data:{  
                    customer_id: $(this).val()
                },
                success: (response) => {   
                    var phone = ''; 
                    phone     += '<label for="customer" class="form-label">'+$(this).data('customerphone')+'</label>';
                    phone     += '<div class="input-group"> ';
                    phone     += '<input type="text" class="form-control form--control" value="'+response.phone+'" disabled />';
                    phone     += '</div>';  
 
                    var address = '';  
                    address += '<label for="customer" class="form-label">'+$(this).data('customeraddress')+'</label>'; 
                    address += '<div class="input-group"> ';
                    address += '<input type="text" class="form-control form--control" value="'+response.address+'" disabled />';
                    address += '</div> ';  
                
                    $('.exists_customer_phone').html(phone);
                    $('.exists_customer_address').html(address);

                },
                error: (error) => {  
                    console.log(error);
                }
            })  

        }
    });


 
}); 
   