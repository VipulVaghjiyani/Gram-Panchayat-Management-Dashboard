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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_currently_living')->nullable()->default(false);
            $table->bigInteger('owner_member_id')->nullable()->default(NULL);
            $table->bigInteger('rental_member_id')->nullable()->default(NULL);
            $table->string('house_no',255)->nullable()->default(NULL);
            $table->text('address');
            $table->string('gaam')->nullable()->default('Baladiya');
            $table->string('taluka')->nullable()->default('Bhuj');
            $table->string('district')->nullable()->default('Kachchh');
            $table->string('state')->nullable()->default('Gujarat');
            $table->string('country')->nullable()->default('India');
            $table->string('post_code')->nullable()->default('370427');
            $table->tinyInteger('total_members')->default(0);
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
        Schema::dropIfExists('houses');
    }
};
