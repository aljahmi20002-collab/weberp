<?php
namespace App\Repositories\Upload;

use App\Models\Upload;
use App\Repositories\Upload\UploadInterface;
use Brick\Math\BigInteger;
use Illuminate\Support\Facades\File;
use Image;
class UploadRepository implements UploadInterface{
    public function upload($folder='',$image_id='',$new_image){
        try {
            //new image store path
            $image_path = '';
            if(!blank($new_image)):
                $image_folder_path = public_path('uploads/'.$folder);
                //new folder create if does not exist this folder
                if(!File::exists(public_path('uploads'))):
                    File::makeDirectory(public_path('uploads'));
                endif;
                if(!File::exists($image_folder_path)):
                    File::makeDirectory($image_folder_path);
                endif;
                $image_name             = date('YmdHisA').\Str::random(5).'.'.$new_image->getClientOriginalExtension();
                $new_image->move($image_folder_path,$image_name);
                $folder                 = !blank($folder)? $folder.'/':$folder;   
                $image_path             = 'uploads/'.$folder.$image_name;
            endif;
            //end new image store path
            $upload  = Upload::find($image_id);
            if($upload):
                if($upload && $new_image && File::exists(public_path($upload->original))):
                    unlink(public_path($upload->original)); //delete existing image
                endif;
            elseif(!blank($new_image)):
                $upload  = new Upload();
            else:
                return null;
            endif;
            if(!blank($image_path)):
                $upload->original   = $image_path;
            endif;
            $upload->save();
            return $upload->id;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function unlinkImage($image_id = ''){
        try {
            $upload  = Upload::find($image_id);
            if($upload && $upload->original && File::exists(public_path($upload->original))):
                unlink(public_path($upload->original));
            endif;
            if($upload):
                $upload->delete();
            endif;
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }


    public function linktoAvatarUpload($user=null,$avatar_original){
        try { 
            //profile upload
            $file             = file_get_contents($avatar_original);
            $file_name        = date('YmdHisA').$user->id.'.jpg';
            File::put(public_path('uploads/profile/').$file_name, $file);
            $file_full_path   = 'uploads/profile/'.$file_name;
            $upload           = new Upload();
            $upload->original = $file_full_path;
            $upload->save();
            //end profile upload

            return $upload->id;

        } catch (\Throwable $th) {

            return null;
        }
    }
    public function linktoImageUpload($folder='',$image){
        try { 
            $image_folder_path = public_path('uploads/'.$folder); 
            if($folder && !File::exists($image_folder_path)):
                File::makeDirectory($image_folder_path); 
            endif;
 
            //link to image upload
            $folder              = $folder !=null ? $folder.'/':'';
            $file                = file_get_contents($image); 
            $file_name           = date('YmdHisA').rand(100000,900000).'.jpg';
            File::put(public_path('uploads/'.$folder).$file_name, $file);
            $file_full_path      = 'uploads/'.$folder.$file_name;
            $upload              = new Upload();
            $upload->original    = $file_full_path; 
            $upload->image_one   = $file_full_path; 
            $upload->image_two   = $file_full_path; 
            $upload->image_three = $file_full_path; 
            $upload->save();
            //end link to image upload
            return $upload->id;

        } catch (\Throwable $th) {
            return null;
        }
    }


    public function uploadImage($image, $path, $image_sizes, $old_upload_id)
    {

        // delete old uploaded images
        if($old_upload_id) {
            $this->deleteImage($old_upload_id, 'update');
        }
        // delete old uploaded images

        $requestImage = $image;
        $info         = getimagesize($image);
        $fileType     = strtolower(image_type_to_extension($info[2]));
        $fileType     = explode('.', $fileType);
        $fileType     = $fileType[1];

        if ($fileType == 'jpg') {
            $fileType = 'jpeg';
        }

        $convertMethod     = 'imagecreatefrom' . $fileType;
        $directory         = "uploads/".$path;
        // $directory      = public_path("uploads/$path");

        // for original images
        $originalImageName = $this->imageName('original', $fileType);
        $originalImageUrl  = $directory . $originalImageName;
        $this->imageSaveToStorage($convertMethod, $originalImageUrl, $requestImage, 'original', '', '');

        $all_url = [];

        foreach ($image_sizes as $key => $image_size) {
            $imageName  = $this->imageName(++$key, 'webp');
            $imageUrl   = $directory . $imageName;
            $all_url[]  = $imageUrl;
            $this->imageSaveToStorage($convertMethod, $imageUrl, $requestImage, '', $image_size[1], $image_size[0]);
        }

        if ($old_upload_id == "") {
            $upload              = new Upload();
        } else {
            $upload              = Upload::find($old_upload_id);
            if(!$upload) {
                $upload              = new Upload();
            }
        } 
        $public_path = public_path()."\uploads";
        // $public_path = public_path()."\uploads";
        $upload->original    = str_replace($public_path, 'uploads', $originalImageUrl);
        $upload->image_one   = @$all_url[0] != "" ? str_replace($public_path, 'uploads', $all_url[0]) : null;
        $upload->image_two   = @$all_url[1] != "" ? str_replace($public_path, 'uploads', $all_url[1]) : null;
        $upload->image_three = @$all_url[2] != "" ? str_replace($public_path, 'uploads', $all_url[2]) : null;
        $upload->save();
        return $upload->id;
    }

    public function imageName($size, $fileType)
    {

        $purpose = substr(0, 20) . $size . '.' . $fileType;
        $purpose = str_replace(" ", "-", $purpose);
        $purpose = date('Y-m-d') . '-' . strtolower(\Str::random(12)) . '-' . $purpose;

        return $purpose;

    }

    public function imageSaveToStorage($convertMethod, $imageUrl, $requestImage, $original, $height = "", $width = "")
    {

        if ($original == 'original') {
            Image::make($convertMethod($requestImage))->save($imageUrl, 90);
        } else {
            if($height == 80 && $width == 80) {
                Image::make($convertMethod($requestImage))->resize($width, $height)->save($imageUrl, 90);
            } else {
                Image::make($convertMethod($requestImage))->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($imageUrl, 90);
            }

        }
        return true;

    }



    public function deleteImage($old_upload_id, $slug = "update")
    {
        $upload = Upload::where('id', $old_upload_id)->first();
        if ($upload) {
            if($upload->original && File::exists(public_path($upload->original))) {
                unlink(public_path($upload->original));
            }
            if($upload->image_one && File::exists(public_path($upload->image_one))) {
                unlink(public_path($upload->image_one));
            }
            if($upload->image_two && File::exists(public_path($upload->image_two))) {
                unlink(public_path($upload->image_two));
            }
            if($upload->image_three && File::exists(public_path($upload->image_three))) {
                unlink(public_path($upload->image_three));
            }

            if ($slug == "delete") {
                $upload->delete();
            }
        }

        return true;
    }


}
