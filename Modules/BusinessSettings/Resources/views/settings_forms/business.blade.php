<div class="tab-pane @if(!old('asset_category') && !$request->branch_page && !$request->tax_rate && !$request->account_heads && !$request->barcode_settings) show active @endif" id="v-pills-business" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
    <form action="{{ route('settings.business.settings.update',['business_id'=>@$business->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row ">
            <div class="col-lg-6  ">
                <label for="business_name" class="form-label">{{ __('business_name') }} <span class="text-danger">*</span></label>
                <input type="text" name="business_name" class="form-control form--control" id="business_name" value="{{ old('business_name',@$business->business_name) }}">
                @error('business_name')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-6  ">
                <label for="start_date" class="form-label">{{ __('start_date') }} <span class="text-danger">*</span></label>
                <input type="text" name="start_date" class="form-control form--control date" id="start_date" value="{{ old('start_date',@$business->start_date) }}">
                @error('start_date')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-4 mt-3">
                <label for="logo" class="form-label">{{ __('logo') }} <span class="text-danger">*</span></label>
                <input type="file" name="logo" class="form-control form--control" id="logo" value="{{ old('logo') }}">
                @error('logo')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-lg-2 mt-3">
                <img src="{{ @$business->logo_img }}" width="100px"/>
            </div>

            <div class="col-lg-6 mt-3">
                <label for="symbol" class="form-label">{{ __('currency') }} <span class="text-danger">*</span></label>
                <select class="form-control form--control select2" name="currency">
                    <option selected disabled>{{ __('select') }} {{ __('currency') }}</option>
                    @foreach (currency() as $currency)
                        <option value="{{ $currency->id }}" @if(old('currency',@$business->currency_id) == $currency->id) selected @endif>{{ $currency->country }} ( {{ $currency->code }} )</option>
                    @endforeach
                </select>
                @error('currency')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-6  ">
                <label for="sku_prefix" class="form-label">{{ __('sku_prefix') }} </label>
                <input type="text" name="sku_prefix" class="form-control form--control" id="sku_prefix" value="{{ old('sku_prefix',@$business->sku_prefix) }}">
                @error('sku_prefix')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-6  ">
                <label for="default_profit_percent" class="form-label">{{ __('default_profit_percent') }}  </label>
                <input type="text" name="default_profit_percent" class="form-control form--control" id="default_profit_percent" value="{{ old('default_profit_percent',@$business->default_profit_percent) }}">
                @error('default_profit_percent')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-lg-6  ">
                <label for="barcode_type" class="form-label">{{ __('barcode_type') }}  </label>
                 <select name="barcode_type" id="barcode_type" class="form-control form--control select2" >
                        @foreach (\Config::get('pos_default.barcode_types') as $key=>$type)
                            <option value="{{ $key }}" @if(@$business->barcode_type == $key) selected @endif>{{ __($type) }}</option>
                        @endforeach
                 </select>
                @error('barcode_type')
                    <p class="text-danger pt-2">{{ $message }}</p>
                @enderror
            </div>
 
            <div class="col-md-12 mt-5 text-end">
                <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
            </div>
        </div>
    </form>
</div>
