"use strict";
$(document).ready(function () { 
 
    function Stripe(){ 
            $('.stripeBtn').on('click',function (e) { 
                var planName  = $(this).data('plan');
                var planPrice = $(this).data('price');
                var planid    = $(this).data('planid');
                var url       = $(this).data('url');
                var publishable_key = $(this).data('publishablekey'); 
                e.preventDefault(); 
                var handler = StripeCheckout.configure({
                    key: publishable_key, // your publisher key id
                    locale: 'auto',
                    token: function(token) { 
                        $('#res_token').html(JSON.stringify(token));
                        $.ajax({
                            url: url,
                            method: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                tokenId: token.id,
                                amount: planPrice, 
                                planid:planid
                            },
                            success: (response) => {
                                if(response.success == true){
                                    toastr.success( 'Subscribe successfully.','Success'); 
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 500);
                                } 
                            },
                            error: (error) => {    
                                toastr.error( 'Something went wrong.','Error'); 
                                console.log(error);
                            }
                        })
                    }
                });
                handler.open({
                    name: planName,
                    description: 'Payment',
                    amount: planPrice * 100
                }); 
        });
    }
        

    Stripe();




});
 