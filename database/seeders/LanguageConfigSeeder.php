<?php

namespace Database\Seeders;

use App\Models\Backend\LanguageConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 1;
        $langConfig->name        = 'English';
        $langConfig->script      = 'Latn';
        $langConfig->native      = 'English';
        $langConfig->regional    = 'en_US';
        $langConfig->save();

        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 2;
        $langConfig->name        = 'Bangla';
        $langConfig->script      = 'Latn';
        $langConfig->native      = 'Bengali';
        $langConfig->regional    = 'bn_BN';
        $langConfig->save();


        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 3;
        $langConfig->name        = 'Arabic';
        $langConfig->script      = 'Arabic';
        $langConfig->native      = 'Saudi Arabia';
        $langConfig->regional    = 'ar_SA';
        $langConfig->save();

        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 4;
        $langConfig->name        = 'Hindi';
        $langConfig->script      = 'Latn';
        $langConfig->native      = 'Indian';
        $langConfig->regional    = 'hi_IN';
        $langConfig->save();

        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 5;
        $langConfig->name        = 'Hebrew';
        $langConfig->script      = 'Hebrew';
        $langConfig->native      = 'Israel';
        $langConfig->regional    = 'il_IL';
        $langConfig->save();


        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 6;
        $langConfig->name        = 'Frence';
        $langConfig->script      = 'Latn';
        $langConfig->native      = 'Frence';
        $langConfig->regional    = 'fr_FR';
        $langConfig->save();


        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 7;
        $langConfig->name        = 'Spanish';
        $langConfig->script      = 'Latn';
        $langConfig->native      = 'Spanish';
        $langConfig->regional    = 'es_ES';
        $langConfig->save();


        $langConfig              = new LanguageConfig();
        $langConfig->language_id = 8;
        $langConfig->name        = 'Turkish';
        $langConfig->script      = 'Turkish';
        $langConfig->native      = 'Turkey';
        $langConfig->regional    = 'tr_TR';
        $langConfig->save();

        
    }
}
