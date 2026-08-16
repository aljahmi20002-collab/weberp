<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Purchase\Enums\PurchasePayStatus;
use Modules\Purchase\Enums\PurchaseStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade'); 
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); 
            $table->string('purchase_no')->nullable();
            $table->unsignedTinyInteger('purchase_status')->default(PurchaseStatus::PENDING)->comment(PurchaseStatus::PENDING.' =  Pending,'.PurchaseStatus::ORDERED.'= Ordered, '.PurchaseStatus::RECEIVED.'= Received'); 
            $table->foreignId('tax_id')->constrained('tax_rates')->onDelete('cascade'); 

            $table->decimal('p_tax_percent',16,2)->nullable(); 
            $table->decimal('total_price',16,2)->nullable(); 
            $table->decimal('p_tax_amount',16,2)->nullable();   
            $table->decimal('total_buy_cost',16,2)->nullable(); 
 
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('business_id');
            $table->index('supplier_id');
            $table->index('purchase_no');
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
        Schema::dropIfExists('purchases');
    }
};
