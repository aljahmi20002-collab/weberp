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
        Schema::create('sale_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedTinyInteger('customer_type')->default(CustomerType::WALK_CUSTOMER)->comment(CustomerType::WALK_CUSTOMER.'= Walk Customer,'.CustomerType::EXISTING_CUSTOMER.'= Existing Customer');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('customer_phone')->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('discount_amount')->default(0);
            $table->foreignId('order_tax_id')->constrained('tax_rates')->onUpdate('cascade');
            $table->longText('shipping_details')->nullable();
            $table->longText('shipping_address')->nullable();
            $table->decimal('shipping_charge')->default(0);
            $table->unsignedTinyInteger('shipping_status')->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            $table->index('business_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('invoice_no');
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
        Schema::dropIfExists('sale_proposals');
    }
};
