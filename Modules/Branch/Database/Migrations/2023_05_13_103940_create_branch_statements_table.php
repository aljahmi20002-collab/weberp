<?php

use App\Enums\StatementType;
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
        Schema::create('branch_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id')->nullable()->constrained('pos')->onDelete('cascade');
            $table->foreignId('pos_payment_id')->nullable()->constrained('pos_payments')->onDelete('cascade');

            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('cascade');
            $table->foreignId('sale_payment_id')->nullable()->constrained('sale_payments')->onDelete('cascade');


            $table->foreignId('service_sale_id')->nullable()->constrained('service_sales')->onDelete('cascade');
            $table->foreignId('service_sale_payment_id')->nullable()->constrained('service_sale_payments')->onDelete('cascade');
           
           
            $table->foreignId('sale_proposal_id')->nullable()->constrained('sale_proposals')->onDelete('cascade');
            $table->foreignId('sale_proposal_payment_id')->nullable()->constrained('sale_proposal_payments')->onDelete('cascade');

            $table->foreignId('income_id')->nullable()->constrained('incomes')->onDelete('cascade');
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->onDelete('cascade');  
            
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedTinyInteger('type')->comment('Income = '.StatementType::INCOME.', Expense = '.StatementType::EXPENSE)->nullable();
            $table->decimal('amount',22,2)->nullable(); 
            $table->longText('note')->nullable();  
            $table->timestamps();

            $table->index('business_id');
            $table->index('sale_id');
            $table->index('branch_id');
            $table->index('income_id');
            $table->index('expense_id');
            $table->index('type');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_statements');
    }
};
