<?php

use App\Enums\Status;
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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->bigInteger('logo')->nullable();
            $table->longText('description')->nullable();
            $table->string('position')->nullable();
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE.' = Active, '.Status::INACTIVE.' = Inactive');
            $table->timestamps();

            
            $table->index('business_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
};
