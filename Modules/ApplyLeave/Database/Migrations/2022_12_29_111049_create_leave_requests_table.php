<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\ApplyLeave\Enums\LeaveStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('business_id')->nullable()->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('leave_assign_id')->constrained('leave_assigns')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('leave_assigns')->onDelete('cascade');
            $table->string('manager')->nullable();
            $table->date('leave_from')->nullable();
            $table->date('leave_to')->nullable();
            $table->string('file')->nullable();
            $table->longText('reason')->nullable();
            $table->unsignedTinyInteger('status')->default(LeaveStatus::PENDING)->comment(LeaveStatus::PENDING.'= Pending, '.LeaveStatus::APPROVED.' = Approved, '.LeaveStatus::REJECTED.' = Rejected');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('business_id');
            $table->index('role_id');
            $table->index('leave_assign_id');
            $table->index('leave_from');
            $table->index('leave_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
};
