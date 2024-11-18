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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bank_id');
            // $table->bigInteger('income_id')->nullable()->default(NULL);
            // $table->bigInteger('expense_id')->nullable()->default(NULL);
            $table->bigInteger('amt_withdrawn')->nullable()->default(NULL);
            $table->bigInteger('amt_deposite')->nullable()->default(NULL);
            $table->bigInteger('amt_remaining')->nullable()->default(NULL);
            $table->string('payment_type');
            $table->string('cheque_number')->nullable()->default(null);
            $table->string('transaction_number')->nullable()->default(null);
            $table->dateTime('transaction_date')->nullable()->default(NULL);
            $table->string('type')->nullable()->default(NULL);
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
        Schema::dropIfExists('bank_transactions');
    }
};
