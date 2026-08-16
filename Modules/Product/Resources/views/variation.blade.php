<tr>
    <td class="align-top " width="15%">
        <div class="variationSelect " >
            <select data-url="{{ route('product.variationvalue') }}" name="variations[]" class="form-control form--control select{{ $id }}  variation_id  variation_select  " id="vari_select{{ $id }}" >
                <option selected disabled >{{ __('select') }} {{ __('variation') }}</option>
                    {!! $options!!} 
            </select>
        </div>
    </td>
    <td >
        <table width="100%" class="product-create" id="prductCreate{{ $id }}">
            <tr id="variation_header">
                <th class="px-3 bg-primary">{{ __('value') }}</th>
                <th class="px-3 bg-primary">{{ __('sku') }}</th>
                <th class="px-3 bg-primary">
                    {{ __('default_purchase_price') }}<br/>
                    <div class="d-flex justify-content-between">
                        <small>Exc. tax</small>
                        <small>Inc. tax	</small>
                    </div>
                </th>
                <th class="px-3 bg-primary">{{ __('margin') }} (%)</th>
                <th class="px-3 bg-primary">
                    {{ __('default_selling_price') }}<br/>
                    <small class="inclusive @if($tax_type != 1) d-none  @endif">Inc. Tax</small>
                    <small class="exclusive @if($tax_type != 2) d-none  @endif">Exc. Tax</small>
                </th>
                <th class="px-3 bg-primary">{{ __('variation_images') }}</th>
                <th class="px-3 bg-primary" id="variationValueAdd{{ $id }}"  data-url="{{ route('product.variationitem') }}"> <label ><i class="fa fa-plus text-white"></i></label></th>
                <th class="px-3 bg-primary"><label class="variation_remove " ><i class="fa fa-trash text-white"></i></label></th>
            </tr>
            <tr>
                <td width="10%">
                    <select class="select{{ $id }}" id="variation_value{{ $id }}"> 
                        <option disabled selected>{{ __('select') }} {{ __('value') }}</option>
                    </select>
                </td>
                <td> 
                    <input type="text" class="form-control form--control variation-input"/> 
                </td>
                <td>
                    <div class="d-flex">
                        <input type="text" class="form-control form--control variation-input  purchese_price_{{ $random_number }} "/>
                        <input type="text" class="form-control form--control variation-input  ml-2 purchese_price_{{ $random_number }} "/>
                    </div>
                </td>
                <td> 
                    <input  type="text" class="form-control form--control variation-input " value="{{ $default_mergin }}" id="purches_mergin_{{ $random_number }}"/>
                </td>
                <td>
                    <input  type="text" class="form-control form--control variation-input " id="selling_price_{{ $random_number }}"  />
                </td>
                <td>
                    <input type="file" class="form-control form--control variation-input"/>
                </td>
                <td>
                   <label   class="variation_value_row_remove"><i class="fa fa-trash"></i></label>
                </td>
                <td> </td>
            </tr>
        </table>
    </td>
</tr>
@include('product::create_script')
