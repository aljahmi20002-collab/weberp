$(document).ready(function(){
    // $("#product_find" ).select2({
    //     ajax: {
    //         url: $('#product_find').data('url'),
    //         type: "POST",
    //         dataType: 'json',
    //         delay: 250,
    //         data: function (params) {
    //             return {
    //                 search: params.term,
    //                 branch_id: $('#branch_id').val(),
    //                 searchQuery: true
    //             };
    //         },
    //         processResults: function (response) { 
    //             return { 
    //                 results: response
    //             };
    //         },
    //         cache: true
    //     } 
    // }); 

    // $("#product_find").change(function(){
    //     if($(this).val() != ''){
    //        $.ajax({
    //             url: $('#variation_location_url').data('url'),
    //             method: 'post',
    //             dataType: 'html',
    //             data:{  
    //                 variation_location_id:$(this).val()
    //             },
    //             success: (response) => { 
    //                 $('#purchase_item_body').append(response);
    //                 $(this).html('<option disabled selected>Enter Product</option>');
    //             },
    //             error: (error) => {  
    //                 console.log(error);
    //             }
    //         })
    //     }
    // });


    $('#applicableTax').change(function(){
            var amount = 0;
            $.ajax({
                url: $(this).data('url'),
                method: 'post',
                dataType: 'html',
                data:{  
                    tax_id:$(this).val()
                },
                success: (response) => { 
                    var taxPercent = parseInt(response);
                    var totalPurchaseCost = parseInt($('#total_purchase_costs').text());
                    var taxAmount     = (totalPurchaseCost/100) * taxPercent;
                    var totalPurchaseCostWithTax = totalPurchaseCost + taxAmount;
                    var amount = totalPurchaseCostWithTax.toFixed(2);
                    var txamount = taxAmount.toFixed(2);
                    $('#totlPurchaseTax').html(txamount);
                    $('#totlPurchasePriceWithTax').html(amount);

                    $('#total_price').val(totalPurchaseCost);
                    $('#total_tax_amount').val(txamount);
                    $('#total_buy_cost').val(amount);
                    
                },
                error: (error) => {  
                    console.log(error);
                }
            })
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

                            $('#purchase_item_body').append(response.view);
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

                        $('#purchase_item_body').append(response);
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
      
            var string = '<div>' + item.label+'</div>'; 
            return $('<li>').append(string).appendTo(ul);
        
    };

 //------------end product search 
 

});