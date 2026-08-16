<?php

use App\Enums\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Service\Enums\ServiceType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->decimal('price',22,2)->default(0);
            $table->string('position')->nullable();
            $table->unsignedTinyInteger('service_type')->default(ServiceType::BUSINESS)->comment(ServiceType::BUSINESS.' = Business, '.ServiceType::SUPERADMIN.' = Supperadmin');
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
        Schema::dropIfExists('services');
    }
};
