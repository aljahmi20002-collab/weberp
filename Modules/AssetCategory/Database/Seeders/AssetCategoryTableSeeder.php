<?php

namespace Modules\AssetCategory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model; 
use Modules\AssetCategory\Entities\AssetCategory;

class AssetCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        $categories = [
            'Mobile Devices',
            'Wearables',
            'TVs',
            'Monitors',
            'Laptops',
            'Tablets',
            'Computers',
            'Computers',
            'Printers',
            'Scanners'
        ];
        foreach ($categories as $key => $value) {
            $assetCategory              = new AssetCategory();
            $assetCategory->business_id = 1;
            $assetCategory->title       = $value;
            $assetCategory->position    = $key;
            $assetCategory->save();
        }   
    }
}
