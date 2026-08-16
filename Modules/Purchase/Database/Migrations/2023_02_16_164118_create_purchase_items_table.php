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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('vari_loc_det_id')->constrained('variation_location_details')->onDelete('cascade');
            $table->decimal('purchase_quantity',22,2)->default(0);
            $table->decimal('unit_cost',22,2)->default(0);
            $table->decimal('total_unit_cost',22,2)->default(0); 
            $table->decimal('profit_percent',22,2)->default(0)->comment('profit percent %');
            $table->decimal('unit_sell_price',22,2)->default(0)->comment('Unit sale price exclue tax');
            $table->timestamps();

            $table->index('purchase_id');
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
        Schema::dropIfExists('purchase_items');
    }
};
