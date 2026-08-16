<?php

use App\Enums\TodoStatus;
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
        Schema::create('todo_list_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('todo_list_id')->constrained('todo_lists')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('status')->default(TodoStatus::PENDING)->comment('pending = 1, processing = 2,complete = 3');
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
        Schema::dropIfExists('todo_list_assigns');
    }
};
