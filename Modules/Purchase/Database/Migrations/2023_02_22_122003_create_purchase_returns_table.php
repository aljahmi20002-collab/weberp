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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade'); 
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); 
            $table->string('return_no')->nullable();
            $table->foreignId('tax_id')->constrained('tax_rates')->onDelete('cascade'); 

            $table->decimal('p_tax_percent',16,2)->nullable(); 
            $table->decimal('total_price',16,2)->nullable(); 
            $table->decimal('p_tax_amount',16,2)->nullable();   
            $table->decimal('total_buy_cost',16,2)->nullable(); 
  
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('business_id');
            $table->index('supplier_id');
            $table->index('return_no');
            $table->index('created_by'); 
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
};
