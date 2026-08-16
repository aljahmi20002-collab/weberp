<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Support\Enums\SupportStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_supports', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onUpdate('cascade')->onDelete('cascade');
            $table->string('priority')->nullable();
            $table->longtext('description')->nullable();
            $table->unsignedBigInteger('attached_file')->nullable(); 
            $table->unsignedTinyInteger('status')->default(SupportStatus::PENDING)->comment(SupportStatus::PENDING.'= Pending,'.SupportStatus::PROCESSING.'= Processing,'.SupportStatus::RESOLVED.'= Resolved,'.SupportStatus::CLOSED.'= Closed');
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
        Schema::dropIfExists('admin_supports');
    }
};
