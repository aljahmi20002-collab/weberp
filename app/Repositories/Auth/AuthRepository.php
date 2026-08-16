<?php
namespace App\Repositories\Auth;

use App\Mail\Auth\ForgotMail;
use App\Mail\Auth\Register;
use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthRepository implements AuthInterface {


    public function passwordResetlink($request){
        try {
            if(settings('mail_driver') == 'sendmail'):
                \config([
                    'mail.mailers.sendmail.path' => settings('sendmail_path'),
                ]);
            endif;

            \config([
                'mail.default'                 => settings('mail_driver'),
                'mail.mailers.smtp.host'       => settings('mail_host'),
                'mail.mailers.smtp.port'       => settings('mail_port'),
                'mail.mailers.smtp.encryption' => settings('mail_encryption'),
                'mail.mailers.smtp.username'   => settings('mail_username'),
                'mail.mailers.smtp.password'   => settings('mail_password'),
                'mail.from.name'               => settings('mail_name')
            ]);


            $token  = $request->otp?? csrf_token();//otp from api
            $email  = $request->email;
            $otp    = $request->otp;//otp from api
            Mail::to($request->email)->send(new ForgotMail($token,$email,$otp));
            $user                 = User::where('email',$email)->first();
            $user->forgot_token   = $token;
            $user->save();
            return true;

        } catch (\Throwable $th) { 
           return false;
        }
    }

     public function passwordUpdate($request){
            try {
                $user = User::where(['email'=>$request->email,'forgot_token'=>$request->token])->first();
                if($user):
                    $user->password      = Hash::make($request->password);
                    $user->forgot_token  = "";
                    $user->save();
                else:
                    return false;
                endif;
               return true;
            } catch (\Throwable $th) {
               return false;
            }
     }


     public function registermailSend($user){
        try {

            if(settings('mail_driver') == 'sendmail'):
                \config([
                    'mail.mailers.sendmail.path' => settings('sendmail_path'),
                ]);
            endif;

            \config([
                'mail.default'                 => settings('mail_driver'),
                'mail.mailers.smtp.host'       => settings('mail_host'),
                'mail.mailers.smtp.port'       => settings('mail_port'),
                'mail.mailers.smtp.encryption' => settings('mail_encryption'),
                'mail.mailers.smtp.username'   => settings('mail_username'),
                'mail.mailers.smtp.password'   => settings('mail_password'),
                'mail.from.name'               => settings('mail_name')
            ]);

            Mail::to($user->email)->send(new Register($user));

            return true;
        } catch (\Throwable $th) {
          
            return false;
        }
     }


     public function verifyNow($request){
         try {
            $user = User::where(['email'=>$request->email,'verify_token'=>$request->verify_token])->first();
            $user->email_verified_at = Date('Y-m-d H:i:s');
            $user->email_verified    = 1;
            $user->verify_token      = null;
            $user->save();
            return true;
         } catch (\Throwable $th) {
            return false;
         }
     }



}
