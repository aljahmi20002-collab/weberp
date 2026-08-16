<?php

namespace Database\Seeders;

use App\Models\Backend\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //general settings
        Setting::create(['title'=>'name',                  'value'=>'WeERP']);
        Setting::create(['title'=>'phone',                 'value'=>'01820000000']);
        Setting::create(['title'=>'email',                 'value'=>'wemaxdevs@gmail.com']);
        Setting::create(['title'=>'logo',                  'value'=>null]);
        Setting::create(['title'=>'favicon',               'value'=>null]);
        Setting::create(['title'=>'table_empty_image',     'value'=>null]);
        Setting::create(['title'=>'table_search_image',    'value'=>null]);
        Setting::create(['title'=>'app_version',           'value'=>'1.0']);
        Setting::create(['title'=>'copyright',             'value'=>'Copyright © 2024 WeERP. All rights reserved. Template Made By WemaxDevs.']);
        Setting::create(['title'=>'default_language',      'value'=>'en']);
        Setting::create(['title'=>'default_display_mode',  'value'=>'night']); 
        Setting::create(['title'=>'theme_background_color','value'=>'#7367f0']);
        Setting::create(['title'=>'theme_text_color',       'value'=>'#ffffff']); 

        //mail settings
        Setting::create(['title' => 'mail_driver',       'value'  => 'smtp']);
        Setting::create(['title' => 'mail_host',        'value'   => 'smtp.gmail.com']);
        Setting::create(['title' => 'sendmail_path',    'value'   => '/usr/sbin/sendmail -bs -i']);
        Setting::create(['title' => 'mail_port',        'value'   => '587']);
        Setting::create(['title' => 'mail_address',     'value'   => 'wemaxit002@gmail.com']);
        Setting::create(['title' => 'mail_name',        'value'   => 'WeERP']);
        Setting::create(['title' => 'mail_username',     'value'  => 'wemaxit002@gmail.com']);
        Setting::create(['title' => 'mail_password',     'value'  => 'fywggxdxphfarwqx']);
        Setting::create(['title' => 'mail_encryption',   'value'  => 'tls']);
        Setting::create(['title' => 'signature',         'value'  => 'WemaxDevs']);

        //social login settings
        //facebook
        Setting::create(['title' => 'facebook_client_id',     'value' => '721479345778370']);
        Setting::create(['title' => 'facebook_client_secret', 'value' => 'a636c53bf10d05b1515a737f853e1fa4']);
        Setting::create(['title' => 'facebook_status',        'value' => 1]);
        //google
        Setting::create(['title' => 'google_client_id',     'value' => '730724862191-cncotvoaiai5vd5hikltrah9df39uou5.apps.googleusercontent.com']);
        Setting::create(['title' => 'google_client_secret', 'value' => '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']);
        Setting::create(['title' => 'google_status',        'value' => 1]);

        //reCaptcha settings
        Setting::create(['title' => 'recaptcha_site_key',          'value' => '6Lcf3yAhAAAAACWKvubI45IoCx8bXgLpcNAHENQV']);
        Setting::create(['title' => 'recaptcha_secret_key',        'value' => '6Lcf3yAhAAAAABaGgYpPwBSKVSXcfXvamu-G07Y9']);
        Setting::create(['title' => 'recaptcha_status',            'value' => 0 ]);
        
        Setting::create(['title' => 'currency',                    'value' => '$' ]);

        //payment settings 
        //stripe
        Setting::create(['title' => 'stripe_publishable_key',     'value' => 'pk_test_csMkzUcsbjbcEuuW6K0QImTT00M403UGkp']);
        Setting::create(['title' => 'stripe_secret_key',          'value' => 'sk_test_aqfYWud5ZhFx0EGIvY6Scdxh00dlfZKnFT']);
        Setting::create(['title' => 'stripe_status',              'value' => 1]);

        //paypal
        Setting::create(['title' => 'paypal_client_id',              'value' => 'ASNysE4ENGfyplv-cNRife5zi8137rEh21yoK4cBZvuy1JWEm-v_DdmfBKVedtmadG1VPgXxUjRg6Q_3']);
        Setting::create(['title' => 'paypal_client_secret',          'value' => 'EJwTIUMb8mjg0EH2gim8jpi8tQaVeomcVm0Rs-c3mjXxcvGR3y6imw1kohYuGs5NCPzJuXe-ggvyixaF']);
        Setting::create(['title' => 'paypal_mode',                   'value' => 'sendbox']);
        Setting::create(['title' => 'paypal_status',                 'value' => 1]);
        //skrill
        Setting::create(['title' => 'skrill_merchant_email',         'value' => 'demoqco@sun-fish.com']);
        Setting::create(['title' => 'skrill_status',                 'value' => 1]);


    }
}
