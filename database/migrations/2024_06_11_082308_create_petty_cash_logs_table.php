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
        Schema::create('petty_cash_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('petty_cash_id');
            $table->string('name');
            $table->double('amount', 10, 2);
            $table->string('type');
            $table->text('description')->nullable();
            $table->bigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_logs');
    }
};
