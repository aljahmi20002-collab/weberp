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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses');
            $table->foreignId('from_account')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->foreignId('to_account')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->decimal('amount',22,2)->default(0); 
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->index('business_id');
            $table->index('from_account');
            $table->index('to_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fund_transfers');
    }
};
