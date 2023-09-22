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
        
        Schema::create('organizations', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->decimal('lunch_price', 10, 2);
            $table->string('currency_code', 4);
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
