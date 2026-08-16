<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Product\Enums\ProductType;
use Modules\Product\Enums\TaxType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignId('warranty_id')->constrained('warranties')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->foreignId('tax_id')->constrained('tax_rates')->onDelete('cascade'); 
            $table->unsignedTinyInteger('barcode_type')->nullable();
            $table->unsignedTinyInteger('enable_stock')->default(0);
            $table->bigInteger('alert_quantity')->default(0); 
            $table->decimal('default_quantity',22,2)->default(0); 
            $table->unsignedBigInteger('image_id')->nullable();
            $table->decimal('purchase_price', 22, 2)->default(0);  
            $table->decimal('sell_price', 22, 2)->nullable();
            $table->foreignId('variation_id')->constrained('variations')->onDelete('cascade');
            $table->longText('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('business_id');
            $table->index('sku');
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('unit_id');
            $table->index('brand_id');
            $table->index('warranty_id');
            $table->index('variation_id');
            $table->index('created_by');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
