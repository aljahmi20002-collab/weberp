<?php
 
use App\Enums\StatementType;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Account\Enums\AccountType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('user_type')->nullable()->comment(AccountType::ADMIN.'= Admin,'.AccountType::BRANCH.' = Branch');
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->foreignId('income_id')->nullable()->constrained('incomes')->onDelete('cascade');
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->onDelete('cascade');
            $table->foreignId('fund_transfer_id')->nullable()->constrained('fund_transfers')->onDelete('cascade');
            $table->foreignId('pur_pay_id')->nullable()->constrained('purchase_payments')->onDelete('cascade');
            $table->foreignId('pur_re_pay_id')->nullable()->constrained('purchase_return_payments')->onDelete('cascade');
            $table->foreignId('sale_pay_id')->nullable()->constrained('sale_payments')->onDelete('cascade');
            $table->foreignId('pos_pay_id')->nullable()->constrained('pos_payments')->onDelete('cascade');
            $table->foreignId('ser_sale_pay_id')->nullable()->constrained('service_sale_payments')->onDelete('cascade');
            $table->foreignId('sale_prop_pay_id')->nullable()->constrained('sale_proposal_payments')->onDelete('cascade');
            
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedTinyInteger('type')->comment('Income = '.StatementType::INCOME.', Expense = '.StatementType::EXPENSE)->nullable();
            $table->decimal('amount',22,2)->nullable(); 
            $table->longText('note')->nullable();  
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
        Schema::dropIfExists('bank_transactions');
    }
};
