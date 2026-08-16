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
        Schema::create('variation_location_details', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade'); 
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); 
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');   
            $table->foreignId('variation_id')->nullable()->constrained('variations')->onDelete('cascade');
            $table->foreignId('product_variation_id')->constrained('product_variations')->onDelete('cascade'); 
            $table->bigInteger('qty_available')->default(0);
            $table->timestamps();

            $table->index('business_id');
            $table->index('branch_id');
            $table->index('product_id');
            $table->index('variation_id');
            $table->index('product_variation_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_location_details');
    }
};
