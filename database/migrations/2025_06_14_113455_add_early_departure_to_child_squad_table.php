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
        Schema::table('child_squad', function (Blueprint $table) {
            $table->date('early_departure_date')->nullable()->comment('Дата досрочного выезда из отряда');
            $table->text('early_departure_reason')->nullable()->comment('Причина досрочного выезда из отряда');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('child_squad', function (Blueprint $table) {
            $table->dropColumn(['early_departure_date', 'early_departure_reason']);
        });
    }
};
