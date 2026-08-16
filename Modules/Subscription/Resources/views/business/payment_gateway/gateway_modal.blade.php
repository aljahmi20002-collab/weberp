<div class="row">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between">
            <div><b>{{ __('plan') }} {{ __('name') }} :</b></div>
            <div>{{ @$plan->name }}</div>
        </div> 
    </div>
    <div class="col-lg-6"> 
        <div class="d-flex justify-content-between">
            <div><b>{{ __('price') }} :</b></div>
            <div>{{  @settings('currency') }} {{ @$plan->price }}</div>
        </div> 
    </div>
    <div id="gateway-content" class="mt-4">
        <div class="row">
            <div class="col-lg-4">
                <button type="button" class="btn payment-gatway-btn stripeBtn" 
                    data-plan={{ @$plan->name }}
                    data-price={{ @$plan->price }}
                    data-planid={{ @$plan->id }}
                    data-url={{ route('business.subscription.stripe.payment') }}
                    data-publishablekey="{{ settings('stripe_publishable_key') }}"
                >
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Stripe_Logo%2C_revised_2016.svg/2560px-Stripe_Logo%2C_revised_2016.svg.png" class="w-100"/>
            </button>
            </div> 
    
            <div class="col-lg-4">
                <button type="button" class="btn payment-gatway-btn modalBtn"
                    data-planid="{{ @$plan->id }}"
                    data-target="#dynamic-modal" 
                    data-url="{{ route('business.subscription.paypal.modal',['plan_id'=>$plan->id]) }}"
                >{{ __('paypal') }}</button>
            </div> 
         
            <div class="col-lg-4">
                <a href="{{ route('business.subscription.skrill.make.payment',['planid'=>$plan->id,'price'=>$plan->price]) }}" class="btn payment-gatway-btn">{{ __('skrill') }}</a>
            </div> 
        </div>
    </div>
</div>  
<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{static_asset('backend')}}/js/subscription/payment.js"></script>  