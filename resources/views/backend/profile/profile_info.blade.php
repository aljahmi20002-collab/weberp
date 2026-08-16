   <!-- Tabs UI Starts -->
   <div class="col-lg-4 ">
    <div class="dashboard--widget mb-3 overflow-auto">
            <div class="row">
                <div class="col-sm-6 mt-2">
                  <span class="font-weight-bold line-height-3" >{{ __('name') }}</span>
                  <p >{{Auth::user()->name}}</p>
                </div>
                <div class="col-sm-6  mt-2">
                    <span class="font-weight-bold line-height-3">{{ __('email') }}</span>
                    <p>{{Auth::user()->email }}</p>
                </div>
                <div class="col-sm-6  mt-2">
                    <span class="font-weight-bold line-height-3">{{ __('phone') }}</span>
                    <p>{{Auth::user()->phone }}</p>
                </div>
                <div class="col-sm-6  mt-2">
                    <span class="font-weight-bold line-height-3">{{ __('address') }}</span>
                    <p>{!! Auth::user()->address !!}</p>
                </div>
                <div class="col-sm-12  mt-2">
                    <span class="font-weight-bold line-height-3">{{ __('about') }}</span>
                    <p>{!! Auth::user()->about !!}</p>
                </div>
                @if(isUser())
                <div class="col-sm-12  mt-2">
                    <span class="font-weight-bold line-height-3">{{ __('branch') }}</span>
                    <p>{!! Auth::user()->branch->name !!}</p>
                </div>
                @endif
            </div>
    </div>
</div>
<!-- Tabs UI Ends -->
