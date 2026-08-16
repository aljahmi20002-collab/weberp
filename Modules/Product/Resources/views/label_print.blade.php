<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <style type="text/css"> 
        /* td{
            border: 1px dotted lightgray;
        } */
        @media  print{ 
            table{
                page-break-after: always;
                border-spacing: 0.1875in 0in;
                overflow: hidden;
            } 
          
            #printbtn{
                display: none;
            } 
   

            @page  {
                size: {{ @$barcode_setting->paper_width.$barcode_setting->paper_width_type}} {{ @$barcode_setting->paper_height.$barcode_setting->paper_height_type}} landscape; 
                margin-top: 0.5in !important;
                margin-bottom: 0.5in !important;
                margin-left: 0.125in !important;
                margin-right: 0.125in !important;
            }
        }
        .single-label{ 
            border: 1px dotted lightgray;
            overflow: hidden !important;
            display: flex;
            flex-wrap: wrap;
            align-content: center;
            width: {{ @$barcode_setting->label_width.$barcode_setting->label_width_type}};
            height: {{ @$barcode_setting->label_height.$barcode_setting->label_height_type}};
            justify-content: center;
        }
        .single-label .title{
            width: {{ @$barcode_setting->label_width.$barcode_setting->label_width_type}}; 
        }
        p{
            margin:0px;
        }

        .business,.title,.variation,.price,.barcode{
            font-size: 12px;
        }
        .barcode{
            margin-top: 0.5em;
        }
        .business{
            font-size: {{ $request->business_name.'px' }};
        }
        .title{
            font-size: {{ $request->product_name.'px' }};
        }
        .variation{
            font-size: {{ $request->variation_name.'px' }};
        }
        .price{
            font-size: {{ $request->product_price.'px' }};
        }
        .barcode{
           transform:scale({{ $request->barcode_scale}}) ;
        }
        .sku{
            font-size: 15px;
            margin-top: 5px;
        }
         
        
    </style>

</head>
<body>
    <div align="right" id="printbtn">
        <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">{{ __('print') }}</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="window.close()">{{ __('close') }}</button>
    </div> 
    @if($barcode_setting->is == 'rolls' || $barcode_setting->is == 'rongta')
        @foreach ($productVariations as $productVariation)                
            <table   align="center" >
                <tr>
                    <td align="center" valign="center"> 
                        <div class="single-label">
                            <div class="single-label-box">
                                @if($request->business_name_show)
                                <p class="business"><b>{{ @Auth::user()->business->business_name}}</b></p>
                                @endif
                                @if($request->product_name_show)
                                <p class="title ">{{ @$product->name }}</p>
                                @endif
                                @if($request->variation_name_show)
                                <p class="variation">{{ @$productVariation->variation->name }} : <b>{{ $productVariation->name }}</b></p>
                                @endif
                                @if($request->product_price_show)
                                <p class="price">{{ __('price') }}: <b>{{ @businessCurrency($productVariation->product->business_id) }} {{ $productVariation->sell_price_inc_tax }}</b></p>
                                @endif
                                <div class="barcode">
                                    {!! $productVariation->sub_barcode_print !!}
                                </div>
                                @if($product->barcode_type == \App\Enums\BarcodeType::C128 || $product->barcode_type == \App\Enums\BarcodeType::C39)
                                <p class="sku">{{ @$productVariation->sub_sku }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </table> 
        @endforeach
    @else
        <table align="center" >
            @php $row = 0; @endphp
            @foreach ($productVariations as $productVariation)    
                @if($row == 0)
                    <tr>     
                @endif
                    @php ++$row; @endphp            
                        <td align="center" valign="center"> 
                            <div class="single-label"> 
                                <div class="single-label-box">
                                    @if($request->business_name_show)
                                    <p class="business"><b>{{ @Auth::user()->business->business_name}}</b></p>
                                    @endif
                                    @if($request->product_name_show)
                                    <p class="title ">{{ @$product->name }}</p>
                                    @endif
                                    @if($request->variation_name_show)
                                    <p class="variation">{{ @$productVariation->variation->name }} : <b>{{ $productVariation->name }}</b></p>
                                    @endif
                                    @if($request->product_price_show)
                                    <p class="price">{{ __('price') }}: <b>{{ @businessCurrency($productVariation->product->business_id) }} {{ $productVariation->sell_price_inc_tax }}</b></p>
                                    @endif
                                    <div class="barcode">
                                        {!! $productVariation->sub_barcode_print !!}
                                    </div>
                                    @if($product->barcode_type == \App\Enums\BarcodeType::C128 || $product->barcode_type == \App\Enums\BarcodeType::C39)
                                    <p class="sku">{{ @$productVariation->sub_sku }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                @if($row == 0)
                </tr>
                @endif
                @php
                    if($row == $barcode_setting->label_in_per_row):
                        $row = 0;
                    endif;
                @endphp
                <style>
                      @if ($barcode_setting->label_in_per_row == 1)    
                        td{
                            page-break-after: always
                        }
                    @endif
                </style>
            @endforeach 
        </table>
    @endif
    <script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
    <script  src="{{static_asset('backend/js')}}/product/labelprint.js"></script>
</body>
</html>