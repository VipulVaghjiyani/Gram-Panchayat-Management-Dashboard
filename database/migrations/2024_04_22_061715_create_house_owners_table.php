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
        Schema::create('house_owners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('house_id')->nullable()->default(NULL);
            $table->bigInteger('member_id')->nullable()->default(NULL);
            $table->string('owner_type')->nullable()->default(NULL);
            $table->bigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_owners');
    }
};
