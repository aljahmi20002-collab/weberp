$(document).ready(function(){
      
 
    $('#applicableTax').change(function(){ 
            $.ajax({
                url: $(this).data('url'),
                method: 'post',
                dataType: 'html',
                data:{  
                    tax_id:$(this).val()
                },
                success: (response) => { 
                    totalCalculation(response);
                },
                error: (error) => {  
                    console.log(error);
                }
            })
    });

    $('#shipping_charge').change(function(){
        $.ajax({
            url: $('#applicableTax').data('url'),
            method: 'post',
            dataType: 'html',
            data:{  
                tax_id:$('#applicableTax').val()
            },
            success: (response) => { 
                totalCalculation(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    });

    $('#discount_amount').change(function(){
        $.ajax({
            url: $('#applicableTax').data('url'),
            method: 'post',
            dataType: 'html',
            data:{  
                tax_id:$('#applicableTax').val()
            },
            success: (response) => { 
                totalCalculation(response);
            },
            error: (error) => {  
                console.log(error);
            }
        })
    });

    function totalCalculation(response){
        var amount = 0;
        var shippingcharge  = parseInt($('#shipping_charge').val());
        var discountamount  = parseInt($('#discount_amount').val());
        if(isNaN(shippingcharge)){
            shippingcharge  = 0;
        }
        if(isNaN(discountamount)){
            discountamount  = 0;
        }

        $('#total_shipping_charge').html(shippingcharge);
        $('#total_discount_amount').html(discountamount)
        
        var taxPercent        = parseInt(response);
        var totalPurchaseCost = parseInt($('#total_purchase_costs').text());
        var taxAmount         = (totalPurchaseCost/100) * taxPercent;
        var totalPurchaseCostWithTax = (totalPurchaseCost + taxAmount + shippingcharge) - discountamount;
        var amount            = totalPurchaseCostWithTax.toFixed(2);
        var txamount          = taxAmount.toFixed(2);
        $('#totlPurchaseTax').html(txamount);
        $('#totlPurchasePriceWithTax').html(amount);

        $('#total_price').val(totalPurchaseCost);
        $('#total_tax_amount').val(txamount);
        $('#total_sell_price').val(amount);
         

    }


    $('#customer_type').change(function(){
        if($(this).val() == $('#existing_customer').val()){
            $('.customer').removeClass('d-none');
            $('.customer').addClass('d-block');
        }else{
            $('.customer').removeClass('d-block');
            $('.customer').addClass('d-none');
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
                            //sound effect
                            var audio = $('#error-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            //end sound effect  
                            toastr.error( 'Product Already added.','Error');  

                        }else{
                                   
                            //sound effect
                            var audio = $('#success-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            //end sound effect  
                            toastr.success( 'Product Added successfully.','Error'); 
                            
                            $('#sale_item_body').append(response.view);
                        } 
                    }else{   
                        if(response.length <=0){

                            //sound effect
                            var audio = $('#error-audio')[0];
                            if (audio !== undefined) {
                                audio.play();
                            }
                            //end sound effect  
                            toastr.error( 'Product was not found.','Error'); 

                        }
                        // products = response;
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
                    //sound effect
                    var audio = $('#error-audio')[0];
                    if (audio !== undefined) {
                        audio.play();
                    }
                    //end sound effect  
                    toastr.error( 'Something went wrong.','Error'); 
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
                            
                        //sound effect
                        var audio = $('#error-audio')[0];
                        if (audio !== undefined) {
                            audio.play();
                        }
                        //end sound effect  
                        toastr.error( 'Product Already added.','Error'); 

                    }else{
                        //sound effect
                        var audio = $('#success-audio')[0];
                        if (audio !== undefined) {
                            audio.play();
                        }
                        //end sound effect  
                        toastr.success( 'Product added successfully.','Success'); 
                        $('#sale_item_body').append(response);
                    }
                   
                },
                error: (error) => {   
                    //sound effect
                    var audio = $('#error-audio')[0];
                    if (audio !== undefined) {
                        audio.play();
                    }
                    //end sound effect  
                    toastr.error( 'Something went wrong.','Error'); 
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

    $('#customer_type').on('change',function(){  
        if($(this).val() != $('#existing_customer').val()){
            $('.walk_customer_phone').removeClass('d-none');
            $('.walk_customer_phone').addClass('d-block');

            $('.exist_customer').removeClass('d-block');
            $('.exist_customer').addClass('d-none');
        }else{
            $('.walk_customer_phone').removeClass('d-block');
            $('.walk_customer_phone').addClass('d-none');
        } 
    });

    $('#customer').on('change',function(){ 

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
 
    });

    
});