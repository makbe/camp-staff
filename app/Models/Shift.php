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
     * Получить детей смены через путевки
     */
    public function children()
    {
        return $this->belongsToMany(Child::class, 'child_shift')
                    ->withPivot([
                        'room_number', 
                        'questionnaire', 
                        'medical_info', 
                        'dietary_requirements', 
                        'roommate_preferences'
                    ])
                    ->withTimestamps();
    }

    /**
     * Получить количество детей на смене
     */
    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }

    /**
     * Получить детей без отрядов на этой смене
     */
    public function getChildrenWithoutSquadsAttribute()
    {
        return $this->children()
                    ->whereDoesntHave('squads', function($query) {
                        $query->where('shift_id', $this->id);
                    })
                    ->get();
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
