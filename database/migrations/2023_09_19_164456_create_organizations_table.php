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

            $table->string('id')->primary(); 
            $table->text('name');
            $table->text('lunch_price');
            $table->text('currency');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
       
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
