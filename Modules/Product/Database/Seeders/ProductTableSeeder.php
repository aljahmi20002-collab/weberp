<?php
namespace Modules\Product\Database\Seeders;

use App\Models\Upload;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::unprepared(file_get_contents(database_path('dummydata/products/products.sql'))); 
    }

}
