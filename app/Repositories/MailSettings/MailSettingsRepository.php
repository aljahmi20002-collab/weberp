<?php
namespace App\Repositories\MailSettings;

use App\Mail\Auth\RegisterMail;
use App\Mail\MailSetting\SendTestMail;

use App\Repositories\MailSettings\MailSettingsInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class MailSettingsRepository implements MailSettingsInterface
{



   public function mailSendTest($request){
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

        Mail::to($request->email)->send(new SendTestMail);
        return true;

       } catch (\Throwable $th) {
          return false;
       }
   }
}
