 
<form action="{{ route('settings.barcode.settings.update',['id'=>$barcodeSetting->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row mt-3">  
        <div class="col-lg-12 mt-3">
            <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form--control" id="name" value="{{ old('name',$barcodeSetting->name) }}">
            @error('name')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label for="paper_width" class="form-label">{{ __('paper_width') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" name="paper_width" step="0.1" class="form-control form--control" id="paper_width" value="{{ old('paper_width',$barcodeSetting->paper_width) }}">
                <div class="input-group-prepend">
                     <select name="paper_width_type" class="form-control form--control input-group-text">
                        <option value="mm" @if($barcodeSetting->paper_width_type == 'mm') selected @endif>{{ __('mm') }}</option>
                        <option value="in" @if($barcodeSetting->paper_width_type == 'in') selected @endif>{{ __('in') }}</option>
                     </select>
                </div>
            </div> 
            @error('paper_width')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label for="paper_height" class="form-label">{{ __('paper_height') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" name="paper_height" step="0.1" class="form-control form--control" id="paper_height" value="{{ old('paper_height',$barcodeSetting->paper_height) }}">
                <div class="input-group-prepend">
                    <select name="paper_height_type" class="form-control form--control input-group-text">
                        <option value="mm" @if($barcodeSetting->paper_height_type == 'mm') selected @endif>{{ __('mm') }}</option>
                        <option value="in" @if($barcodeSetting->paper_height_type == 'in') selected @endif>{{ __('in') }}</option>
                    </select>
               </div>
            </div>
            @error('paper_height')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label for="label_width" class="form-label">{{ __('label_width') }} <span class="text-danger">*</span></label>
            <div class="input-group"> 
                <input type="number" name="label_width" step="0.1" class="form-control form--control" id="label_width" value="{{ old('label_width',$barcodeSetting->label_width) }}">
                <div class="input-group-prepend">
                    <select name="label_width_type" class="form-control form--control input-group-text">
                        <option value="mm" @if($barcodeSetting->label_width_type == 'mm') selected @endif>{{ __('mm') }}</option>
                        <option value="in" @if($barcodeSetting->label_width_type == 'in') selected @endif>{{ __('in') }}</option>
                    </select>
               </div>
            </div>

            @error('label_width')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label for="label_height" class="form-label">{{ __('label_height') }} <span class="text-danger">*</span></label>

            <div class="input-group"> 
                <input type="number" name="label_height" step="0.1" class="form-control form--control" id="label_height" value="{{ old('label_height',$barcodeSetting->label_height) }}"> 
                <div class="input-group-prepend">
                    <select name="label_height_type" class="form-control form--control input-group-text">
                        <option value="mm" @if($barcodeSetting->label_height_type == 'mm') selected @endif>{{ __('mm') }}</option>
                        <option value="in" @if($barcodeSetting->label_height_type == 'in') selected @endif>{{ __('in') }}</option>
                    </select>
                </div>
            </div> 

            @error('label_height')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label for="label_in_per_row" class="form-label">{{ __('label_in_per_row') }} <span class="text-danger">*</span></label>
            <input type="number" name="label_in_per_row" class="form-control form--control" id="label_in_per_row" value="{{ old('label_in_per_row',$barcodeSetting->label_in_per_row) }}">
            @error('label_in_per_row')
                <p class="text-danger pt-2">{{ $message }}</p>
            @enderror
        </div>
 
        <div class="col-md-12 mt-5 text-end">
            <button type="submit" class="btn submit-btn btn-primary btn-sm"> <i class="fa fa-save"></i> {{__('update')}}</button>
        </div>
    </div>
</form> 
