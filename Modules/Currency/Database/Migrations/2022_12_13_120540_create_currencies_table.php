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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable();
            $table->string('currency')->nullable();
            $table->string('code')->nullable();
            $table->string('symbol')->nullable();
            $table->unsignedBigInteger('status')->default(Status::ACTIVE)->comment('Active= '.__('status.'.Status::ACTIVE).', Inactive = '.__('status.'.Status::INACTIVE));
            $table->bigInteger('position')->nullable();
            $table->timestamps();

            $table->index('symbol');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
