<?php

namespace Modules\Unit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Entities\Unit;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard(); 
        $units = [
            ['name'=> 'Coli Stationary (100Pc(s))','short_name'=>'Coli'],   
            ['name'=> 'Grams','short_name'=>'g'],
            ['name'=> 'Packets','short_name'=>'packets'],	   
            ['name'=> 'Pieces','short_name'=>'pieces']
        ];
        $i=0;
        foreach ($units as  $value) {
            $unit               = new Unit();
            $unit->business_id  = 1;
            $unit->name         = $value['name'];
            $unit->short_name   = $value['short_name'];
            $unit->position     = ++$i;
            $unit->save();
        }
    }
}
