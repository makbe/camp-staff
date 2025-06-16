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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('full_name'); // ФИО ребенка
            $table->date('birth_date'); // Дата рождения
            $table->foreignId('squad_id')->nullable()->constrained('squads')->onDelete('set null'); // Связь с отрядом
            $table->text('notes')->nullable(); // Дополнительные заметки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
