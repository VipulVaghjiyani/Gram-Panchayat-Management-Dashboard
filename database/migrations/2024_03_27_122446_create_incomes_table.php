<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('house_id');
            $table->bigInteger('member_id');
            $table->bigInteger('account_id')->nullable()->default(NULL);
            $table->string('financial_year')->nullable()->default(NULL);
            $table->date('paid_date')->nullable()->default(NULL);
            $table->tinyInteger('no_of_year')->nullable()->default(NULL);
            $table->bigInteger('income_category_id');
            $table->double('amount', 10, 2);
            $table->string('payment_type');
            $table->string('bank_name')->nullable()->default(null);
            $table->string('cheque_number')->nullable()->default(null);
            $table->string('transaction_number')->nullable()->default(null);
            $table->date('transaction_date')->nullable()->default(null);
            $table->date('to_date')->nullable()->default(NULL);
            $table->date('from_date')->nullable()->default(NULL);
            $table->boolean('is_taxable')->nullable()->default(false);
            $table->boolean('is_late_paid')->nullable()->default(false);
            $table->text('note')->nullable()->default(NULL);
            $table->bigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
