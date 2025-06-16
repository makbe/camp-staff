<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Переносим существующие связи в новую таблицу
        $staffWithShifts = DB::table('staff')
            ->whereNotNull('shift_id')
            ->get(['id', 'shift_id']);
            
        foreach ($staffWithShifts as $staff) {
            DB::table('shift_staff')->insert([
                'staff_id' => $staff->id,
                'shift_id' => $staff->shift_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Удаляем старый столбец shift_id из таблицы staff
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Добавляем обратно столбец shift_id
        Schema::table('staff', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->after('id')->constrained();
        });
        
        // Восстанавливаем данные из промежуточной таблицы (берем первую смену)
        $shiftStaff = DB::table('shift_staff')
            ->select('staff_id', DB::raw('MIN(shift_id) as shift_id'))
            ->groupBy('staff_id')
            ->get();
            
        foreach ($shiftStaff as $relation) {
            DB::table('staff')
                ->where('id', $relation->staff_id)
                ->update(['shift_id' => $relation->shift_id]);
        }
    }
};
