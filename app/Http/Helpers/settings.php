<?php

// settings helper

use App\Models\Backend\Setting;
use App\Models\Upload;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

//settings
if(!function_exists('settings')){
    function settings($title=""){ 
 
        $settings = Cache::remember('settings', 86400, function () {
            return Setting::all();
        });

        $settings = $settings->where('title', $title)->first();

         if($settings):
            if($title == 'favicon'){
                return favicon($settings->value);
            }elseif($title == 'logo'){
                return logo($settings->value);
            }elseif($title == 'table_empty_image'){
                return table_empty_image($settings->value);
            }elseif($title == 'table_search_image'){
                return table_search_image($settings->value);
            }else{
                return $settings->value;
            }
         endif;
        return null;
    }
}
//end settings helpers

//logo
if(!function_exists('logo')){
    function logo($upload_id=null){
        $logo   = Upload::find($upload_id);
        if($logo && File::exists(public_path($logo->original))):
            return static_asset($logo->original);
        endif;
        return static_asset('logo.png');
    }
}
//end logo


//favicon
if(!function_exists('favicon')){
    function favicon($upload_id=null){
        $favicon   = Upload::find($upload_id);
        if($favicon && File::exists(public_path($favicon->original))):
            return static_asset($favicon->original);
        endif;
        return static_asset('favicon.png');
    }
}
//end favicon


//table empty image
if(!function_exists('table_empty_image')){
    function table_empty_image($upload_id=null){
        $table_empty_image   = Upload::find($upload_id);
        if($table_empty_image && File::exists(public_path($table_empty_image->original))):
            return static_asset($table_empty_image->original);
        endif;
        return static_asset('backend/images/default/no-data-available.png');
    }
}
//end table empty image
//favicon
if(!function_exists('table_search_image')){
    function table_search_image($upload_id=null){
        $table_search_image   = Upload::find($upload_id);
        if($table_search_image && File::exists(public_path($table_search_image->original))):
            return static_asset($table_search_image->original);
        endif;
        return static_asset('backend/images/default/search-data-image.png');
    }
}
//end favicon

