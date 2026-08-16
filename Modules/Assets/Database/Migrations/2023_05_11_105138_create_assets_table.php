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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('asset_category_id')->constrained('asset_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('supplier')->nullable();
            $table->string('quantity')->nullable();
            $table->string('warranty')->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('amount',22,2)->nullable();
            $table->longtext('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
