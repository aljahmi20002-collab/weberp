<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Attendance\Enums\AttendanceStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('in_ip_address')->nullable();
            $table->string('out_ip_address')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('stay_time')->nullable()->comment('Minutes');
            $table->unsignedTinyInteger('status')->default(AttendanceStatus::PENDING)->comment(AttendanceStatus::PENDING.' = Pending,'.AttendanceStatus::CHECK_OUT.' = Cehcked out');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('business_id');
            $table->index('branch_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
