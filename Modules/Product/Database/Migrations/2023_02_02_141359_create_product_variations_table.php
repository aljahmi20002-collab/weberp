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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id(); 
            $table->string('sub_sku')->nullable(); 
            $table->string('name')->nullable(); 
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');    
            $table->foreignId('variation_id')->nullable()->constrained('variations')->onDelete('cascade');
            $table->decimal('default_purchase_price', 22, 2)->default(0); 
            $table->decimal('profit_percent', 22, 2)->default(0);
            $table->decimal('default_sell_price', 22, 2)->nullable();
            $table->decimal('sell_price_inc_tax', 22, 2)->comment("Sell price including tax")->nullable();
            $table->timestamps();

            $table->index('sub_sku');
            $table->index('product_id');
            $table->index('variation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
};
