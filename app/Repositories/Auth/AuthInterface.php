<?php
namespace App\Repositories\Auth;
Interface AuthInterface {
    public function passwordResetlink($request);
    public function passwordUpdate($request);
    public function registermailSend($user);
    public function verifyNow($request);

}
