<?php

namespace Modules\BusinessSettings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Modules\Business\Entities\Business;
use Modules\BusinessSettings\Entities\BarcodeSettings;

class BarcodeSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();  
        foreach (Config::get('pos_default.label_settings') as $label) {
            $label = (object)$label;
            $barcodeSetting                 = new BarcodeSettings(); 
            $barcodeSetting->name           = $label->name;
            $barcodeSetting->paper_width    = $label->paper_width;
            $barcodeSetting->paper_height   = $label->paper_height;
            $barcodeSetting->label_width    = $label->label_width;
            $barcodeSetting->label_height   = $label->label_height;
            $barcodeSetting->label_in_per_row  = $label->label_in_per_row;
            $barcodeSetting->is             = $label->is;
            $barcodeSetting->default        = 1;

            $barcodeSetting->paper_width_type  = $label->paper_width_type;
            $barcodeSetting->paper_height_type = $label->paper_height_type;
            $barcodeSetting->label_width_type  = $label->label_width_type;
            $barcodeSetting->label_height_type = $label->label_height_type;
            $barcodeSetting->save();
        }  
    }
}
