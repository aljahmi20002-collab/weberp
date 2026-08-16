<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Customer\Enums\CustomerType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedTinyInteger('customer_type')->default(CustomerType::WALK_CUSTOMER)->comment(CustomerType::WALK_CUSTOMER.'= Walk Customer,'.CustomerType::EXISTING_CUSTOMER.'= Existing Customer');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('customer_phone')->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('selling_price',22,2)->default(0); 
            $table->foreignId('order_tax_id')->constrained('tax_rates')->onUpdate('cascade');
            $table->longText('shipping_details')->nullable();
            $table->longText('shipping_address')->nullable(); 
            $table->unsignedTinyInteger('shipping_status')->nullable();

            $table->decimal('order_tax_percent',16,2)->nullable(); 
            $table->decimal('total_price',16,2)->nullable(); 
            $table->decimal('order_tax_amount',16,2)->nullable(); 
            $table->decimal('shipping_charge',22,2)->default(0);
            $table->decimal('discount_amount',22,2)->default(0);
            $table->decimal('total_sell_price',16,2)->nullable(); 
 
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            $table->index('business_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('invoice_no');
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
        Schema::dropIfExists('service_sales');
    }
};
