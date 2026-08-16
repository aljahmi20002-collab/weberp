<?php

namespace Database\Seeders;

use App\Models\Backend\RecaptchaSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecaptchaSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * ReCaptcha keys are intentionally empty — configure them from the
     * admin panel (Settings → ReCaptcha) after installation.
     *
     * @return void
     */
    public function run()
    {
        $recaptcha              = new RecaptchaSetting();
        $recaptcha->site_key    = '';
        $recaptcha->secret_key  = '';
        $recaptcha->status      = 0;
        $recaptcha->save();
    }
}
