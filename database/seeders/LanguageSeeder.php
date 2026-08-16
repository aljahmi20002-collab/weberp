<?php

namespace Database\Seeders;

use App\Models\Backend\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $lang                 = new Language();
        $lang->name           = 'English';
        $lang->code           = 'en';
        $lang->icon_class     = 'flag-icon flag-icon-us';
        $lang->text_direction = 'LTR';
        $lang->save();


        $lang                 = new Language();
        $lang->name           = 'Bangla';
        $lang->code           = 'bn';
        $lang->icon_class     = 'flag-icon flag-icon-bd';
        $lang->text_direction = 'LTR';
        $lang->save();


        $lang                 = new Language();
        $lang->name           = 'Arabic';
        $lang->code           = 'ar';
        $lang->icon_class     = 'flag-icon flag-icon-sa';
        $lang->text_direction = 'RTL';
        $lang->save();
        
        $lang                 = new Language();
        $lang->name           = 'Hindi';
        $lang->code           = 'in';
        $lang->icon_class     = 'flag-icon flag-icon-in';
        $lang->text_direction = 'LTR';
        $lang->save();
        

        $lang                 = new Language();
        $lang->name           = 'Hebrew';
        $lang->code           = 'he';
        $lang->icon_class     = 'flag-icon flag-icon-il';
        $lang->text_direction = 'RTL';
        $lang->save();

        $lang                 = new Language();
        $lang->name           = 'French';
        $lang->code           = 'fr';
        $lang->icon_class     = 'flag-icon flag-icon-fr';
        $lang->text_direction = 'LTR';


        $lang->save();  $lang = new Language();
        $lang->name           = 'Spanish';
        $lang->code           = 'es';
        $lang->icon_class     = 'flag-icon flag-icon-es';
        $lang->text_direction = 'LTR';
        $lang->save();

        $lang->save();  $lang = new Language();
        $lang->name           = 'Turkish';
        $lang->code           = 'tr';
        $lang->icon_class     = 'flag-icon flag-icon-tr';
        $lang->text_direction = 'LTR';
        $lang->save();


    }
}
