$(document).ready(function(){ 
 
        var url    = $('#paypal_plan').data('url');
        var price  = $('#paypal_plan').data('price');
        var planid = $('#paypal_plan').data('planid');
 
        const fundingSources = [
            paypal.FUNDING.PAYPAL,
            paypal.FUNDING.CREDIT,
            paypal.FUNDING.CARD
        ]; 
        for (const fundingSource of fundingSources) {
            const paypalButtonsComponent = paypal.Buttons({
                fundingSource: fundingSource,
        
                // optional styling for buttons
                // https://developer.paypal.com/docs/checkout/standard/customize/buttons-style-guide/
        
                style: {
                    shape: "rect",
                    height: 40,
                    label: "subscribe"
                },
          
                // set up the transaction
                createOrder: (data, actions) => {  
                    const createOrderPayload = {
                        purchase_units: [
                            {
                                amount: {
                                    value: price
                                }
                            }
                        ]
                    }; 
                    return actions.order.create(createOrderPayload);
                },
                // notify the buyer that the subscription is successful
                onApprove: (data, actions) => {
                   
                    const captureOrderHandler = (details) => {
                        const payerName = details.payer.name.given_name; 
                    };
                    $.ajax({
                              url: url,
                              method: 'get',
                              data: { 
                                  planid:  planid,
                                  orderID: data.orderID,
                              },
                              success: (response) => {  
                                if(response.success == true){
                                    toastr.success('Something went wrong.','Success');  
                                    setTimeout(() => {
                                        window.location.reload();
                                    },500);
                                } 
                              },
                              error: (error) => { 
                                toastr.error('Something went wrong.','Error');   
                              }
                          });
                    return actions.order.capture().then(captureOrderHandler);
                },
        
                // handle unrecoverable errors
                onError: (error) => {
                  
                    if(parseInt(amount) <=0){
                        toastr.error('This plan amount not payable.','Error');   
                    }else{
                        toastr.error('Something went wrong.','Error');   
                    }
                    
                }
            });
        
            if (paypalButtonsComponent.isEligible()) {
                paypalButtonsComponent
                    .render("#paypal-button-container")
                    .catch((err) => {
                        console.log(err);
                    });
            }
        }
});
