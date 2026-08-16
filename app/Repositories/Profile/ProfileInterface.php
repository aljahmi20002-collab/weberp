<?php
namespace App\Repositories\Profile;

Interface  ProfileInterface {
     public function profileUpdate($request);
     public function updateAccount($request);
     public function updatePassword($request);
     public function updateAvatar($request);

}
