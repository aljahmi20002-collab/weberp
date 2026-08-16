<?php

use App\Enums\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Account\Enums\AccountType;
use Modules\Account\Enums\PaymentGateway;
use Modules\Plan\Enums\IsDefault;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedTinyInteger('account_type')->comment(AccountType::ADMIN.'= Admin, ' .AccountType::BRANCH.'= Branch');
            $table->unsignedTinyInteger('payment_gateway')->comment(PaymentGateway::CASH.'= Cash, ' .PaymentGateway::BANK.'= Bank'.PaymentGateway::MOBILE.'= Mobile');
            $table->string('bank_name')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('branch_name')->nullable(); 
            $table->string('mobile')->nullable();
            $table->string('number_type')->nullable();
            $table->decimal('balance',22,2)->default(0);
            $table->unsignedTinyInteger('is_default')->default(IsDefault::NO);
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE.'='.trans('status.'.\App\Enums\Status::ACTIVE).', ' .\App\Enums\Status::INACTIVE.'='.trans('status.'.\App\Enums\Status::INACTIVE));
            $table->timestamps();

            $table->index('business_id');
            $table->index('branch_id');
            $table->index('account_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
