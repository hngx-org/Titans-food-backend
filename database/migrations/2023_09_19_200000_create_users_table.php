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


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_pic')->nullable();
            $table->string('email')->unique();
            $table->unsignedBigInteger('phone')->unique()->nullable();
            $table->string('password_hash');
            $table->boolean('is_admin')->default(false);
            $table->string('lunch_credit_balance')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('bank_number')->nullable()->unique();
            $table->string('bank_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_region')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_code')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
