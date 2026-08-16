<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;
    public function __construct($user=null)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user          = $this->user;
        $signature     = settings('signature');
        $site_name     = settings('name');
        $contact_email = settings('email');
        $logo          = settings('logo'); 
        $copyright     = settings('copyright');
 
        
        return $this->from(settings('mail_address'),settings('mail_name'))->view('auth.verify-email',compact('user','signature','site_name','logo','copyright','contact_email'))->subject('Account active Notification');
    }
}
