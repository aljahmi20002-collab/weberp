<?php
namespace App\Repositories\Upload;

Interface UploadInterface {
    public function upload($folder,$image_id,$new_image);//folder name,  exiting image id,   new image
    public function unlinkImage($image_id);//only pass image id
    public function linktoAvatarUpload($user=null,$avatar_original);//user = passing user , avatar_original = online avatar image link
    public function linktoImageUpload($folder,$image); //folder, image
   
   //resize image
    public function uploadImage($image, $path, $image_sizes, $old_upload_id);
    public function deleteImage($old_upload_id, $slug);
}
