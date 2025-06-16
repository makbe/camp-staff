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
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['early_departure_date', 'early_departure_reason']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->date('early_departure_date')->nullable()->comment('Дата досрочного выезда');
            $table->text('early_departure_reason')->nullable()->comment('Причина досрочного выезда');
        });
    }
};
