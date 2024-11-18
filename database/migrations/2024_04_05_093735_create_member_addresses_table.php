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
        Schema::create('member_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('member_id')->nullable()->default(NULL);
            $table->text('permanent_address')->nullable()->default(NULL);
            $table->boolean('is_same_as_permanent_address')->nullable()->default(false);
            $table->text('current_address')->nullable()->default(NULL);
            $table->string('gaam')->nullable()->default(NULL);
            $table->string('taluka')->nullable()->default(NULL);
            $table->string('district')->nullable()->default(NULL);
            $table->string('state')->nullable()->default(NULL);
            $table->string('country')->nullable()->default(NULL);
            $table->string('post_code')->nullable()->default(NULL);
            $table->string('house_hold_type')->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_addresses');
    }
};
