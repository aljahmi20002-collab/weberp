<?php

use App\Enums\BarcodeType;
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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->nullable();
            $table->string('start_date')->nullable();
            $table->foreignId('logo')->nullable()->constrained('uploads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('barcode_type')->default(BarcodeType::C128);
            $table->string('sku_prefix')->nullable();
            $table->float('default_profit_percent', 5, 2)->default(0);
            $table->unsignedBigInteger('status')->default(Status::ACTIVE)->comment('Active= '.__('status.'.Status::ACTIVE).', Inactive = '.__('status.'.Status::INACTIVE));
            $table->timestamps();

            $table->index('owner_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
};
