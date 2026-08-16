<?php

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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('account_head_id')->nullable()->constrained('account_heads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('from_account')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->foreignId('to_account')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->decimal('amount',22,2)->default(0);
            $table->longText('note')->nullable();
            $table->foreignId('document_id')->nullable()->constrained('uploads')->onUpdate('cascade');
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade');
            $table->timestamps();

            $table->index('business_id');
            $table->index('branch_id');
            $table->index('account_head_id');
            $table->index('from_account');
            $table->index('to_account');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
