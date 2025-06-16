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
        Schema::create('squads', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название отряда
            $table->text('description')->nullable(); // Описание отряда
            $table->integer('max_children')->default(20); // Максимальное количество детей
            $table->integer('current_children')->default(0); // Текущее количество детей
            $table->string('age_group')->nullable(); // Возрастная группа (например, "6-8 лет")
            
            // Связь со сменой
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            
            // Связь с вожатой (сотрудником)
            $table->foreignId('counselor_id')->nullable()->constrained('staff')->onDelete('set null');
            
            $table->timestamps();
            
            // Индексы
            $table->index(['shift_id']);
            $table->index(['counselor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('squads');
    }
};
