@extends('backend.partials.master')
@section('title',__('subscriptions'))
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">{{ __('subscriptions') }}</h5>
        <ul class="breadcrumb">
            <li class="active"> <a href="#">{{ __('subscriptions') }}</a>  </li>  
        </ul>
    </div>
@endsection 
@section('maincontent') 
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                @foreach ($plans as $key =>$plan)
                    <div class="col-xl-4">
                        <div class="dashboard--widget  text-center p-5 plan-card">  
                            <div class="plan-card-2">
                                <h3 class="text-center my-2">{{ @$plan->name }}</h3>
                                <p class="my-3">{{ @$plan->description }}</p> 
                               <div class="d-flex justify-content-center my-5 ">
                                    <h3 class="mt-2px">{{ settings('currency') }} {{@$plan->price}} </h3>
                                    <div class="mx-2 text-left">
                                       <p class="mb-2 font-weight-bold"> {{ @$currency->code }} / {{  @$plan->intval_name }}</p>
                                       <p  >when billed annually</p>
                                    </div>
                               </div>
                               <ul class="list-style-none text-left plan-accordion">
                                   <li><i class="fa fa-check text-success mr-10px"></i>Total user or employee count {{ @$plan->user_count }}</li>
                                    @php
                                        $hrmCheck = 0;
                                    @endphp
                                    @foreach ($plan->modules as $module)
                                        @if(in_array($module,$plan->hrm_modules))
                                            @php $hrmCheck += 1; @endphp
                                        @endif
                                    @endforeach 
                                    @if($hrmCheck > 0)
                                    <li  class="accordion" id="accordionExample{{ $plan->id }}">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne{{ $plan->id }}">
                                              <button class="accordion-button hrmBtn px-0 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $plan->id }}" aria-expanded="false" aria-controls="collapseOne{{ $plan->id }}">
                                                <i class="fa fa-list mr-10"></i> {{ __('hrm') }}
                                              </button>
                                            </h2>
                                            <div id="collapseOne{{ $plan->id }}" class="accordion-collapse collapse " aria-labelledby="headingOne{{ $plan->id }}"    data-bs-parent="#accordionExample{{ $plan->id }}">
                                              <div class="accordion-body px-0">
                                                    @foreach ($plan->modules as $module)
                                                        @if(in_array($module,$plan->hrm_modules))
                                                            <p><i class="fa fa-check text-success mr-10px"></i>{{ __(@$module) }}</p>
                                                        @endif
                                                    @endforeach
                                              </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @foreach ($plan->modules as $module)
                                        @if(!in_array($module,$plan->hrm_modules))  
                                            <li><i class="fa fa-check text-success mr-10px"></i>{{__(@$module) }}</li>
                                        @endif
                                    @endforeach 
                               </ul>
                            </div>
                            <div class="align-bottom"   @if (!isSubscribed()) style="margin-top:-28px" @endif>
                                @if(@Auth::user()->business->subscription->plan->id == $plan->id)

                                @if (!isSubscribed())
                                    <div><span class="text-danger font-weight-bold text-uppercase  ">{{ __('expired') }}</span></div>
                                <button class="btn btn-primary subscribe-btn modalBtn" data-url="{{ route('business.subscription.payment.gateway',['plan_id'=>$plan->id]) }}" data-title="Upgrade {{ @$plan->name }} Plan" data-bs-toggle="modal" data-modalsize="modal-lg" data-bs-target="#dynamic-modal" >Subscribe</button>
                                @else
                                    <div><span class="text-success font-weight-bold text-uppercase  ">{{ __('activated') }}</span></div>
                                @endif
                                @else
                                    <button class="btn btn-primary subscribe-btn modalBtn" data-url="{{ route('business.subscription.payment.gateway',['plan_id'=>$plan->id]) }}" data-title="Upgrade {{ @$plan->name }} Plan" data-bs-toggle="modal" data-modalsize="modal-lg" data-bs-target="#dynamic-modal" >Subscribe</button>
                                @endif
                            </div>
                        </div>
                    </div> 
                @endforeach
            </div>
        </div>
    </div>  
@endsection
  
@push('scripts')
    <script src = "https://checkout.stripe.com/checkout.js" > </script>
    <script src="https://www.paypal.com/sdk/js?client-id={{ settings('paypal_client_id') }}&currency=USD&vault=true&intent=capture" data-sdk-integration-source="integrationbuilder"></script> 
@endpush