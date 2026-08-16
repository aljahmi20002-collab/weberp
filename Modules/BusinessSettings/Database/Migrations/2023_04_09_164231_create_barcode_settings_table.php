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
        Schema::create('barcode_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->nullable(); 
            $table->string('paper_width')->nullable();
            $table->string('paper_height')->nullable();
            $table->string('label_width')->nullable();
            $table->string('label_height')->nullable(); 
            $table->integer('label_in_per_row')->default(1); 
 
            $table->string('paper_width_type')->nullable();
            $table->string('paper_height_type')->nullable();
            $table->string('label_width_type')->nullable();
            $table->string('label_height_type')->nullable();

            $table->string('is')->default('custom'); 
            $table->string('default')->default(0); 
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
        Schema::dropIfExists('barcode_settings');
    }
};
