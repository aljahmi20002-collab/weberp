<?php

use App\Enums\StatementType;
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
        Schema::create('account_heads', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('type')->default(StatementType::INCOME)->comment(StatementType::INCOME.'= Income'.StatementType::EXPENSE.'= Expense');
            $table->string('name')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('is_default')->default(Status::ACTIVE)->comment('Active= '.__('status.'.Status::ACTIVE).', Inactive = '.__('status.'.Status::INACTIVE));
            $table->unsignedBigInteger('status')->default(Status::ACTIVE)->comment('Active= '.__('status.'.Status::ACTIVE).', Inactive = '.__('status.'.Status::INACTIVE));
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
        Schema::dropIfExists('account_heads');
    }
};
