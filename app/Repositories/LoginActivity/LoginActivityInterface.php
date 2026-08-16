<?php
namespace App\Repositories\LoginActivity;

Interface LoginActivityInterface {
    public function getLatest();
    public function addLoginActivity($user_agent,$activity);//$activity = pass your activity name , $user_agent = pass user agent information
}
