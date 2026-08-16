<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $token,$email,$otp;
    public function __construct($token = null,$email =null,$otp=null)
    {
        $this->token = $token;
        $this->email = $email;
        $this->otp   = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token     = $this->token;
        $email     = $this->email;
        $otp       = $this->otp;
        $signature = settings('signature');
        $site_name = settings('name');
        $logo      = settings('logo');
        $copyright = settings('copyright');
      
        return $this->from(settings('mail_address'),settings('mail_name'))->view('auth.passwords.send_reset_link',compact('token','signature','email','site_name','logo','copyright','otp'))->subject('Reset Password Notification');
    }
}
