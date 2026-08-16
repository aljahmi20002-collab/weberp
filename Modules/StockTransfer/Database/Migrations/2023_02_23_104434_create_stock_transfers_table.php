<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\StockTransfer\Enums\StockTransferStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade'); 
            $table->foreignId('from_branch')->constrained('branches')->onUpdate('cascade')->onDelete('cascade'); 
            $table->foreignId('to_branch')->constrained('branches')->onUpdate('cascade')->onDelete('cascade'); 
            $table->string('transfer_no')->nullable(); 
            $table->decimal('shipping_charge',22,2)->default(0);
            $table->decimal('total_transfer_amount',22,2)->default(0);
            $table->unsignedTinyInteger('status')->default(StockTransferStatus::PENDING)->comment(StockTransferStatus::PENDING.' = pending,'.StockTransferStatus::IN_TRANSIT.'= In-Transit,'.StockTransferStatus::COMPLETED.'= Completed'); 
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps(); 

            $table->index('business_id');
            $table->index('from_branch'); 
            $table->index('to_branch'); 
            $table->index('transfer_no'); 
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
        Schema::dropIfExists('stock_transfers');
    }
};
