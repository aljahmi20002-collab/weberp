@extends('backend.partials.master')
@section('title') 
    @if(business())
        {{ __('support') }} {{ __('view') }}
    @else
        {{ __('request') }} {{ __('view') }}
    @endif 
@endsection 
@section('breadcrumb')
    <div class="breadcrumb-area">
        <h5 class="title text--base m-0">
            {{ @$title }}
        </h5>
        <ul class="breadcrumb">
            <li> <a href="#"> {{ @$title }} </a> </li> 
            <li>
                @if(business())
                  {{ __('view') }}
                @else
                   {{ __('view') }}
                @endif
            </li>
        </ul>
    </div>
@endsection
@section('maincontent')
    <div class="user-panel-wrapper">
        <div class="user-panel-content">
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="dashboard--widget ">
                       <h5>{{ @$support->subject }}</h5>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="dashboard--widget ">
                        <div class="d-flex">
                            <span class="w-30">{{ __('name') }}</span>
                            <span class="w-70">: {{ @$support->user->name }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="w-30">{{ __('email') }}</span>
                            <span class="w-70">: {{ @$support->user->email }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="w-30">{{ __('business_name') }}</span>
                            <span class="w-70">: {{ @$support->user->business->business_name }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="w-30">{{ __('service') }}</span>
                            <span class="w-70">: {{ @$support->service->name }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="w-30">{{ __('department') }}</span>
                            <span class="w-70">: {{ @$support->department->name }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="w-30">{{ __('priority') }}</span>
                            <span class="w-70">: {{ @__($support->priority) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">

                    <div class="accordion mb-4" id="accordionExample">
                        <div class="accordion-item">
                            <div class="card">
                                <div class="card-header">
                                    <a href="#" class="text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="{{ $errors->any() == true? 'true':'false' }}" aria-controls="collapseOne">
                                      <i class="fa fa-reply"></i> {{ __('reply') }}
                                    </a>
                                </div> 
                                <div id="collapseOne" class="accordion-collapse collapse {{ $errors->any() == true? 'show':'' }}" data-bs-parent="#accordionExample">
                                    <div class="card-body p-0">
                                        <div class="accordion-body">
                                            <form  action="{{ route('support.reply',['support_id'=>$support->id,'reply'=>'reply']) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="message" class="form-label">{{ __('message') }} <span class="text-danger">*</span></label>
                                                    <textarea class="form-control form--control" name="message">{{ old('message') }}</textarea>
                                                    @error('message')
                                                        <p class="text-danger pt-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="attached_file" class="form-label">{{ __('attached_file') }}</label>
                                                    <input class="form-control form--control" name="attached_file" type="file" id="attached_file" />  
                                                </div> 
                                                <div class="text-right mt-3">
                                                    <button type="submit" class="btn btn-sm btn-primary">{{ __('send') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>  
                    </div>

                    @foreach ($chats as $chat)      
                        {{-- requested message --}}
                        <div class="chat-box dashboard--widget">
                            <div class="chat-header d-flex justify-content-between align-items-center"> 
                                <div class="d-flex w-70 align-items-center">
                                    <img src="{{ @$chat->user->image }}" class="rounded-circle" height="50" width="50"/>
                                    <div class="ml-10px">
                                        <p class="mb-0"><b>{{@$chat->user->name }}</b></p>
                                        <span class="badge badge-pill badge-primary"> {{ @$chat->user_type }}</span>
                                    </div> 
                                </div> 
                                <span><small>{{ @$chat->created_date_time }}</small></span>
                            </div>
                            <div class="chat-body mt-3">
                                {!! @$chat->message !!}
                            </div> 
                            @if(!empty($chat->download_file))
                                <div class="chat-footer border-top mt-3">
                                    <a class="text-primary" href="{{ $chat->download_file }}" download="">{{ __('download') }}</a>
                                </div>
                            @endif
                        </div> 
                        {{--requested message --}}
                    @endforeach
 
                    {{-- first requested message --}}
                    <div class="chat-box dashboard--widget">
                        <div class="chat-header d-flex justify-content-between align-items-center"> 
                            <div class="d-flex w-70 align-items-center">
                                <img src="{{ @$support->user->image }}" class="rounded-circle" height="50" width="50"/>
                                <div class="ml-10px">
                                    <p class="mb-0"><b>{{@$support->user->name }}</b></p>
                                    <span class="badge badge-pill badge-primary"> {{ @$support->user_type }}</span>
                                </div> 
                            </div> 
                             <span><small>{{ @$support->created_date_time }}</small></span>
                        </div>
                        <div class="chat-body mt-3">
                            {!! @$support->description !!}
                        </div>
                        @if(!empty($support->download_file))
                            <div class="chat-footer border-top mt-3">
                                 <a class="text-primary" href="{{ $support->download_file }}" download="">{{ __('download') }}</a>
                            </div>
                        @endif
                    </div> 
                    {{-- end requested message --}} 
                </div>

            </div>
        </div>
    </div>
@endsection
