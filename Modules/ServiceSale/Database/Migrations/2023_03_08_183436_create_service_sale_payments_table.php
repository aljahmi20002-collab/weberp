<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Purchase\Enums\PaymentMethod;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_sale_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_sale_id')->constrained('service_sales')->onDelete('cascade');
            $table->unsignedTinyInteger('payment_method')->default(PaymentMethod::CASH)->comment(PaymentMethod::CASH.'= Cash, '.PaymentMethod::BANK.' = Bank');
            $table->string('bank_holder_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->decimal('amount',22,2)->default(0);
            $table->longText('description')->nullable();
            $table->date('paid_date')->nullable();
            $table->unsignedBigInteger('document_id')->nullable();//come from uploads table
            $table->timestamps(); 
            
            $table->index('service_sale_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_sale_payments');
    }
};
