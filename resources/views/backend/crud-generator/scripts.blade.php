<script type="text/javascript" >
    var i = 1;
    var row ="";
    row += '<div class="row mt-3">';
    row +=  '<div class="col-lg-3">';
    row += '<label for="field_name" class="form-label">{{ __("field_name") }}</label>';
    row += '<input type="text" name="field[][field_name]" class="form-control form--control" id="field_name" value="{{ old("field_name") }}">';
    @error('field_name')
    row += '<p class="text-danger pt-2">{{ $message }}</p>';
    @enderror
    row += '</div>';

    row += '<div class="col-lg-3">';
    row += '<label for="db_type" class="form-label">{{ __("db_type") }}</label>';
    row += '<select class="form-control form--control" name="field[][db_type]" height="40px" id="db_type">';
    row +='<option value="increments"  >INCREMENTS</option>';
    row +='<option value="bigIncrements"  >BIG INCREMENTS</option>';
    row +='<option value="timestamps"  >TIME STAMPS</option>';
    row +='<option value="softDeletes"  >SOFT DELETES</option>';
    row +='<option value="rememberToken"  >REMEMBER TOKEN</option>';
    row +='<option disabled="disabled"  >-</option>';
    row +='<option value="string" selected >STRING</option>';
    row +='<option value="text"  >TEXT</option>';
    row +='<option disabled="disabled"  >-</option>';
    row +='<option value="tinyInteger"  >TINY INTEGER</option>';
    row +='<option value="smallInteger"  >SMALL INTEGER</option>';
    row +='<option value="mediumInteger"  >MEDIUM INTEGER</option>';
    row +='<option value="integer"  >INTEGER</option>';
    row +='<option value="bigInteger"  >BIG INTEGER</option>';
    row +='<option disabled="disabled"  >-</option>';
    row +='<option value="float"  >FLOAT</option>';
    row +='<option value="decimal"  >DECIMAL</option>';
    row +='<option value="boolean"  >BOOLEAN</option>';
    row +='<option disabled="disabled"  >-</option>';
    row +='<option value="enum"  >ENUM</option>';
    row +='<option disabled="disabled"  >-</option>';
    row +='<option value="date"  >DATE</option>';
    row +='<option disabled="disabled"  >-</option>';
    row +='<option value="binary"  >BINARY</option>';
    row +='</select>';

    @error('db_type')
    row +='<p class="text-danger pt-2">{{ $message }}</p>';
    @enderror
    row +='</div>';
    row +='<div class="col-lg-3">';
    row += '<label for="html_type" class="form-label">{{ __("html_type") }}</label>';
    row +='<select class="form-control form--control" name="field[][html_type]" height="40px" id="db_type">';

    row +='<option value="text" >Text</option>';
    row +='<option value="email" >Email</option>';
    row +='<option value="number" >Number</option>';
    row +='<option value="date" >Date</option>';
    row +='<option value="file" >File</option>';
    row +='<option value="password" >Password</option>';
    row +='<option value="select" >Select</option>';
    row +='<option value="radio" >Radio</option>';
    row +='<option value="checkbox" >Checkbox</option>';
    row +='<option value="textarea" >TextArea</option>';
    row +='</select>';
    @error('html_type')
    row +='<p class="text-danger pt-2">{{ $message }}</p>';
    @enderror
    row +='</div>';
    row +='<div class="col-lg-1">';
    row +='<label for="field_name" class="form-label">{{ __("required") }}</label>';
    row +='<div>';
    row +='<input type="checkbox" name="field[][required]" class="required-check mt-2" />';
    row += '</div>';
    row +='</div>';
    row +='<div class="col-lg-2">';
    row +='<label  class="form-label">{{ __("action") }}</label>';
    row +='<div>';
    row +='<button type="button" id="actionBtn" class="action-btn text-danger mt-2" >';
    row +='<i class="fas fa-trash-alt" style="font-size: 20px"></i>';
    row +='</button>';
    row += '</div>';
    row +='</div>';
    row +='</div>';
   $('#add_field_btn').click(function(){
       $('#field_row').append(row);

   });


</script>
