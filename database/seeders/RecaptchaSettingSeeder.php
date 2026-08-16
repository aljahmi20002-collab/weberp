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
     * @return void
     */
    public function run()
    {
        $recaptcha              = new RecaptchaSetting();
        $recaptcha->site_key    = '6Lcf3yAhAAAAACWKvubI45IoCx8bXgLpcNAHENQV';
        $recaptcha->secret_key  = '6Lcf3yAhAAAAABaGgYpPwBSKVSXcfXvamu-G07Y9';
        $recaptcha->status      = 0;
        $recaptcha->save();
    }
}
