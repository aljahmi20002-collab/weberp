<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('script')->nullable();
            $table->string('native')->nullable();
            $table->string('regional')->nullable();
            $table->timestamps();

            $table->index('language_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_configs');
    }
};
