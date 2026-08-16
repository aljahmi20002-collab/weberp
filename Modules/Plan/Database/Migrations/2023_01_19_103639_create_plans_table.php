<?php

use App\Enums\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Plan\Enums\IsDefault;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id(); 
            $table->string('name')->nullable();
            $table->bigInteger('user_count')->default(0); 
            $table->bigInteger('days_count')->default(0);
            $table->decimal('price',22,2)->default(0);
            $table->longText('description')->nullable(); 
            $table->longText('options')->nullable(); 
            $table->bigInteger('position')->default(0);
            $table->longText('modules')->nullable();
            $table->unsignedTinyInteger('is_default')->default(IsDefault::NO);
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE.' = Active, '.Status::INACTIVE.' = Inactive');
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
        Schema::dropIfExists('plans');
    }
};
