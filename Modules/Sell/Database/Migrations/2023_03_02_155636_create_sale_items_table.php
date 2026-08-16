<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('vari_loc_det_id')->constrained('variation_location_details')->onDelete('cascade');
            $table->decimal('sale_quantity',22,2)->default(0);
            $table->decimal('unit_price',22,2)->default(0);
            $table->decimal('total_unit_price',22,2)->default(0);   
            $table->timestamps();

            $table->index('sale_id');
            $table->index('business_id');
            $table->index('branch_id');
            $table->index('vari_loc_det_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_items');
    }
};
