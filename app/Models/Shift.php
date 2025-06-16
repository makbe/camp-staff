<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Получить всех сотрудников смены
     */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'shift_staff')->withTimestamps();
    }

    /**
     * Получить отряды смены
     */
    public function squads(): HasMany
    {
        return $this->hasMany(Squad::class);
    }

    /**
     * Проверить, активна ли смена
     */
    public function isActive(): bool
    {
        $now = now()->startOfDay();
        $startDate = $this->start_date->startOfDay();
        $endDate = $this->end_date->endOfDay();
        
        return $now->between($startDate, $endDate);
    }
}
