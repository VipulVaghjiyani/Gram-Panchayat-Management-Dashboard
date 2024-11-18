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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_income_member')->nullable()->default(false);
            $table->boolean('is_expance_member')->nullable()->default(false);
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->date('dob')->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
            $table->string('mobile');
            $table->string('alternate_number')->nullable()->default(NULL);
            $table->bigInteger('main_member_id')->nullable()->default(NULL);
            $table->bigInteger('house_id')->nullable()->default(NULL);
            $table->bigInteger('expense_id')->nullable()->default(NULL);
            /* $table->text('permanent_address')->nullable()->default(NULL);
            $table->boolean('is_same_as_permanent_address')->nullable()->default(false);
            $table->text('current_address')->nullable()->default(NULL);
            $table->string('gaam')->nullable()->default('Baladiya');
            $table->string('taluka')->nullable()->default('Bhuj');
            $table->string('district')->nullable()->default('Kachchh');
            $table->string('state')->nullable()->default('Gujarat');
            $table->string('country')->nullable()->default('India');
            $table->string('post_code')->nullable()->default('370427'); */
            $table->string('house_hold_type')->nullable()->default(NULL);
            $table->boolean('is_house_exist')->nullable()->default(false);
            $table->bigInteger('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
