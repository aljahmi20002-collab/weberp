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
    <div class="col-lg-12 mt-3" >
        <div id="paypal-button-container"></div>
    </div>
    <input id="paypal_plan" data-url="{{ route('business.subscription.paypal.payment') }}" data-price={{ $plan->price }} data-planid={{ $plan->id }} type="hidden"/>
</div>

<script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{static_asset('backend')}}/js/subscription/paypal.js"></script>  
