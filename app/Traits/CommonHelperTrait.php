<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait CommonHelperTrait {

    protected function modelImageProcessor($images, $defaultImages)
    {  
        $data = $defaultImages;

        if($images  && $images->original['original'] && File::exists(public_path($images->original['original']))):
            $data['original'] = static_asset($images->original['original']);
        endif;
        if($images  && $images->original['image_one'] && File::exists(public_path($images->original['image_one']))):
            $data['image_one'] = static_asset($images->original['image_one']);
        endif;
        if($images  && $images->original['image_two'] && File::exists(public_path($images->original['image_two']))):
            $data['image_two'] = static_asset($images->original['image_two']);
        endif;
        if($images  && $images->original['image_three'] && File::exists(public_path($images->original['image_three']))):
            $data['image_three'] = static_asset($images->original['image_three']);
        endif;
        return $data;
    }

}
