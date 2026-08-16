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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->date('start_date')->nullable(); 
            $table->date('end_date')->nullable();
            $table->decimal('plan_price',22,2)->nullable();
            $table->string('paid_via')->nullable(); 
            $table->timestamps();

            $table->index('plan_id');
            $table->index('business_id');
            $table->index('start_date');
            $table->index('end_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
