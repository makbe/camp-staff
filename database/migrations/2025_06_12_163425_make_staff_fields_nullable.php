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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();
            $table->string('first_name')->nullable()->change();
            $table->enum('position', [
                'вожатый', 
                'методист', 
                'декоратор', 
                'фотограф', 
                'видеограф', 
                'хореограф', 
                'старший воспитатель', 
                'психолог', 
                'другое'
            ])->nullable()->change();
            $table->foreignId('shift_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('last_name')->nullable(false)->change();
            $table->string('first_name')->nullable(false)->change();
            $table->enum('position', [
                'вожатый', 
                'методист', 
                'декоратор', 
                'фотограф', 
                'видеограф', 
                'хореограф', 
                'старший воспитатель', 
                'психолог', 
                'другое'
            ])->nullable(false)->change();
            $table->foreignId('shift_id')->nullable(false)->change();
        });
    }
};
