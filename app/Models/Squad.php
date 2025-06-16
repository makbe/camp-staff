<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'current_children',
        'age_group',
        'shift_id',
        'counselor_id',
    ];

    protected $casts = [
        'current_children' => 'integer',
    ];

    /**
     * Отряд принадлежит смене
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Отряд может иметь вожатую (сотрудника)
     */
    public function counselor()
    {
        return $this->belongsTo(Staff::class, 'counselor_id');
    }

    /**
     * Отряд имеет много детей
     */
    public function children()
    {
        return $this->belongsToMany(Child::class)->withTimestamps();
    }

    /**
     * Получить текущее количество детей в отряде
     */
    public function getCurrentChildrenCount()
    {
        return $this->children()->count();
    }

    /**
     * Возможные возрастные группы
     */
    public static function getAgeGroups(): array
    {
        return [
            '6-8 лет',
            '9-11 лет',
            '12-14 лет',
            '15-17 лет',
            'Смешанная',
        ];
    }
}
