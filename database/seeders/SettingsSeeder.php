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
     * NOTE: Third-party credentials (mail, social, reCAPTCHA, Stripe, PayPal,
     * Skrill) are intentionally seeded as empty / placeholder values so that
     * no real credentials are bundled with the source code. Configure them
     * from the admin panel after installation, or override via the admin
     * "Settings" pages.
     *
     * @return void
     */
    public function run()
    {

        //general settings
        Setting::create(['title'=>'name',                  'value'=>'WebERP']);
        Setting::create(['title'=>'phone',                 'value'=>'01820000000']);
        Setting::create(['title'=>'email',                 'value'=>'admin@example.com']);
        Setting::create(['title'=>'logo',                  'value'=>null]);
        Setting::create(['title'=>'favicon',               'value'=>null]);
        Setting::create(['title'=>'table_empty_image',     'value'=>null]);
        Setting::create(['title'=>'table_search_image',    'value'=>null]);
        Setting::create(['title'=>'app_version',           'value'=>'1.0']);
        Setting::create(['title'=>'copyright',             'value'=>'Copyright © 2024 WebERP. All rights reserved.']);
        Setting::create(['title'=>'default_language',      'value'=>'en']);
        Setting::create(['title'=>'default_display_mode',  'value'=>'night']); 
        Setting::create(['title'=>'theme_background_color','value'=>'#7367f0']);
        Setting::create(['title'=>'theme_text_color',       'value'=>'#ffffff']); 

        //mail settings (configure via admin → settings → mail)
        Setting::create(['title' => 'mail_driver',       'value'  => 'smtp']);
        Setting::create(['title' => 'mail_host',        'value'   => 'smtp.mailtrap.io']);
        Setting::create(['title' => 'sendmail_path',    'value'   => '/usr/sbin/sendmail -bs -i']);
        Setting::create(['title' => 'mail_port',        'value'   => '587']);
        Setting::create(['title' => 'mail_address',     'value'   => 'noreply@example.com']);
        Setting::create(['title' => 'mail_name',        'value'   => 'WebERP']);
        Setting::create(['title' => 'mail_username',    'value'   => '']);
        Setting::create(['title' => 'mail_password',    'value'   => '']);
        Setting::create(['title' => 'mail_encryption',  'value'  => 'tls']);
        Setting::create(['title' => 'signature',        'value'  => 'WebERP']);

        //social login settings (configure via admin panel)
        //facebook
        Setting::create(['title' => 'facebook_client_id',     'value' => '']);
        Setting::create(['title' => 'facebook_client_secret', 'value' => '']);
        Setting::create(['title' => 'facebook_status',        'value' => 0]);
        //google
        Setting::create(['title' => 'google_client_id',     'value' => '']);
        Setting::create(['title' => 'google_client_secret', 'value' => '']);
        Setting::create(['title' => 'google_status',        'value' => 0]);

        //reCaptcha settings (configure via admin panel)
        Setting::create(['title' => 'recaptcha_site_key',   'value' => '']);
        Setting::create(['title' => 'recaptcha_secret_key', 'value' => '']);
        Setting::create(['title' => 'recaptcha_status',     'value' => 0 ]);
        
        Setting::create(['title' => 'currency',             'value' => '$' ]);

        //payment settings (configure via admin panel)
        //stripe
        Setting::create(['title' => 'stripe_publishable_key', 'value' => '']);
        Setting::create(['title' => 'stripe_secret_key',      'value' => '']);
        Setting::create(['title' => 'stripe_status',          'value' => 0]);

        //paypal
        Setting::create(['title' => 'paypal_client_id',      'value' => '']);
        Setting::create(['title' => 'paypal_client_secret',  'value' => '']);
        Setting::create(['title' => 'paypal_mode',           'value' => 'sandbox']);
        Setting::create(['title' => 'paypal_status',         'value' => 0]);
        //skrill
        Setting::create(['title' => 'skrill_merchant_email', 'value' => '']);
        Setting::create(['title' => 'skrill_status',         'value' => 0]);
    }
}
