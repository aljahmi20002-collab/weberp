<?php
namespace App\Repositories\Profile;

use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\Upload\UploadInterface;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileRepository implements ProfileInterface {
    protected $upload;
    public function __construct(UploadInterface $upload)
    {
        $this->upload    = $upload;
    }

    public function profileUpdate($request){
        try {
             $user          = User::find(Auth::user()->id);
             $user->name    = $request->name;
             $user->save();

             return true;
        } catch (\Throwable $th) {
           return false;
        }
    }

    public function updateAccount($request){
       try {

           $user                 = User::find(Auth::user()->id);
           $user->phone          = $request->phone; 
           $user->address        = $request->address;
           $user->about          = $request->about;
           $user->save();
           return true;
       } catch (\Throwable $th) {
           return false;
       }
    }

    //profile image update
    public function updateAvatar($request){

        try {
            $user           = User::find(Auth::user()->id);
            $user->avatar   = $this->upload->upload('profile',Auth::user()->avatar,$request->avatar);
            $user->save();
            return true;
        } catch (\Throwable $th) {

            return false;
        }
    }

    //profile password update
    public function updatePassword($request){
            try {
                $user = User::find(Auth::user()->id);
                if(!$user):
                    return false;
                endif;

                if(Hash::check($request->current_password,$user->password)):
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    return true;
                else:
                    return 'invalid_password';
                endif;
                return false;
            } catch (\Throwable $th) {
                return false;
            }
    }

}
