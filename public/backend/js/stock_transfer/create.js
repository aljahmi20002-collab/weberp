$(document).ready(function(){
    $('#branch_id').change(function(){
        $('#stock_transfer_item_body').html('');
        $('#total_purchase_costs').html(0); 
        $('#shipping_charge').val(0); 
        $('#totlPurchaseTax').html(0); 
        $('#totlPurchasePriceWithTax').html(0);
        $('#total_item').html(0);
        $('#total_amount').val(0); 
    });

    

    $('#shipping_charge').change(function(){
            var amount = 0;  
            var shipping_charge = 0;
                shipping_charge = parseInt($(this).val());
               if(isNaN(shipping_charge)){
                shipping_charge = 0;
               }
            var totalAmount = parseInt($('#total_purchase_costs').text()); 
            var totalAmountWithCharge = totalAmount + shipping_charge;
            var amount = totalAmountWithCharge.toFixed(2);
            var charge = shipping_charge.toFixed(2);
            $('#totlPurchaseTax').html(charge);
            $('#totlPurchasePriceWithTax').html(amount); 
            $('#total_amount').val(amount); 
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
                            toastr.success( 'Product added successfully.','Success');  
                            $('#stock_transfer_item_body').append(response.view);
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
                        $('#stock_transfer_item_body').append(response);
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
 

});