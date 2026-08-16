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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->foreignId('file')->nullable()->constrained('uploads')->onDelete('cascade');
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE.' = Active,'.Status::INACTIVE.' =  Inactive');
            $table->longText('note')->nullable();
            $table->timestamps();

            $table->index('from');
            $table->index('to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holidays');
    }
};
