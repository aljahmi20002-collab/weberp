<div class="tab-pane @if(!$request->todo_list) show active @endif" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="row g-4 customer-profile"> 
        <div class="col-xl-4">
            <div class="dashboard--widget height100"> 
                <h5>{{ __('user_profile') }}</h5>
                <div class="d-flex align-items-center mt-3">
                    <div >
                        <img class="rounded-circle mr-20" src="{{ @$user->image }}" width="50"/>
                    </div>
                    <div class="line-height-2">
                        {{ @$user->name }} <br/>
                        {{ @$user->email }} 
                    </div>
                </div>
            </div>
        </div>  
        <div class="col-xl-8">
            <div class="dashboard--widget height100">
                <h5>{{ __('user_info') }}</h5>
                <div class="row mt-3"> 
                    @if($user->user_type  == \App\Enums\UserType::USER)
                        <div class="col-xl-4 mt-2">
                            <div class="d-flex ">
                                <p class="mb-0">{{ __('branch') }}:</p>
                                <p>{{ @$user->branch->name }} </p>
                            </div>
                        </div>  
                    @endif
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('phone') }}:</p>
                            <p>{{ $user->phone }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 ">{{ __('user_type') }}:</p>
                            <p>{{ $user->user_types }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 ">{{ __('designation') }}:</p>
                            <p>{{ @$user->designation->name }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 ">{{ __('department') }}:</p>
                            <p>{{ @$user->department->name }}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('address') }}:</p>
                            <p>{{ $user->address }}</p>
                        </div>
                    </div>  
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('is_ban') }}:</p>
                            <p>{!! $user->BanType !!}</p>
                        </div>
                    </div> 
                    <div class="col-xl-4 mt-2">
                        <div class="d-flex ">
                            <p class="mb-0 w-20">{{ __('status') }}:</p>
                            <p>{!! $user->MyStatus !!}</p>
                        </div>
                    </div> 
                </div>
            </div>
        </div>   
    </div>
    @if(!isSuperadmin())
        <div class="row ">  
            <div class="col-xl-4 mt-3"> 
                <div class="dashboard--widget"> 
                    <h5>{{ __('todo') }}</h5>
                    <div class="chart-area">
                        <div id="todo_pie_chart" class="chart"></div>
                    </div> 
                </div> 
            </div>
            <div class="col-xl-8">
                <div class="row height100"> 
                    <div class="col-xl-6  mt-3">
                        <div class="dashboard--widget height100"> 
                            <div class="d-flex align-items-center height100">
                                <div>
                                <i class="fa fa-list fontsize35px mr-20"></i>
                                </div>
                                <div class="line-height-2">
                                    <span>{{ __('total_todo') }}</span> <br/>
                                    <span class="fontsize20px"> {{ @$totalTodo['total_todo'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6  mt-3">
                        <div class="dashboard--widget height100"> 
                            <div class="d-flex align-items-center height100">
                                <div>
                                <i class="fa fa-spinner fontsize35px mr-20"></i>
                                </div>
                                <div class="line-height-2">
                                    <span>{{ __('total_pendings') }}</span> <br/>
                                    <span class="fontsize20px"> {{ $totalTodo['total_pending'] }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6  mt-3">
                        <div class="dashboard--widget height100">
                            <div class="d-flex align-items-center height100">
                                <div  >   
                                <i class="fa fa-list-check fontsize35px mr-20"></i>
                                </div>
                                <div class="line-height-2">
                                    <span>{{ __('total_processing') }}</span>  <br/>
                                    <span class="fontsize20px"> {{ $totalTodo['total_processing'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mt-3">
                        <div class="dashboard--widget height100 "> 
                            <div class="d-flex align-items-center height100">
                                <div  >
                                <i class="fa fa-check fontsize35px mr-20"></i>
                                </div>
                                <div class="line-height-2">
                                    <span>{{ __('total_completed') }}</span> <br/>
                                    <span class="fontsize20px"> {{ $totalTodo['total_completed'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 
        </div> 
    @endif
</div>

@push('scripts')
    @if(!isSuperadmin())
        @include('backend.user.view-content.chart_js')
    @endif
@endpush    