<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'birth_date',
        'notes',
        'avatar',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Отношение к отрядам (многие ко многим)
     */
    public function squads()
    {
        return $this->belongsToMany(Squad::class)
                    ->withPivot(['early_departure_date', 'early_departure_reason'])
                    ->withTimestamps();
    }

    /**
     * Отношение к путевкам (многие ко многим со сменами)
     */
    public function vouchers()
    {
        return $this->belongsToMany(Shift::class, 'child_shift')
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
     * Получить все смены, в которых участвует ребенок
     */
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'child_shift')
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
     * Проверить, есть ли у ребенка путевка на указанную смену
     */
    public function hasVoucherForShift($shiftId)
    {
        return $this->shifts()->where('shift_id', $shiftId)->exists();
    }

    /**
     * Получить путевку ребенка для указанной смены
     */
    public function getVoucherForShift($shiftId)
    {
        return $this->shifts()->where('shift_id', $shiftId)->first();
    }

    /**
     * Вычисляет возраст ребенка
     */
    public function getAgeAttribute()
    {
        return $this->birth_date->age;
    }

    /**
     * Возвращает возраст в формате "X лет"
     */
    public function getAgeTextAttribute()
    {
        $age = $this->age;
        
        if ($age % 10 == 1 && $age % 100 != 11) {
            return $age . ' год';
        } elseif (in_array($age % 10, [2, 3, 4]) && !in_array($age % 100, [12, 13, 14])) {
            return $age . ' года';
        } else {
            return $age . ' лет';
        }
    }

    /**
     * Возвращает дату рождения в формате d.m.Y
     */
    public function getBirthDateFormattedAttribute()
    {
        return $this->birth_date->format('d.m.Y');
    }

    /**
     * Проверяет, участвует ли ребенок в указанном отряде
     */
    public function isInSquad($squadId)
    {
        return $this->squads()->where('squad_id', $squadId)->exists();
    }

    /**
     * Проверяет, участвует ли ребенок в указанной смене
     */
    public function isInShift($shiftId)
    {
        return $this->squads()->whereHas('shift', function($query) use ($shiftId) {
            $query->where('id', $shiftId);
        })->exists();
    }

    /**
     * Получить текущие активные отряды (по датам смен)
     */
    public function getCurrentSquads()
    {
        return $this->squads()->whereHas('shift', function($query) {
            $query->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        });
    }

    /**
     * Получить все отряды с информацией о сменах
     */
    public function getSquadsWithShiftsAttribute()
    {
        return $this->squads()->with('shift')->get();
    }

    /**
     * Проверяет, уехал ли ребенок досрочно из конкретного отряда
     */
    public function hasEarlyDepartureFromSquad($squadId)
    {
        $squad = $this->squads()->where('squad_id', $squadId)->first();
        return $squad && !is_null($squad->pivot->early_departure_date);
    }

    /**
     * Получить информацию о досрочном выезде из конкретного отряда
     */
    public function getEarlyDepartureFromSquad($squadId)
    {
        $squad = $this->squads()->where('squad_id', $squadId)->first();
        if (!$squad || is_null($squad->pivot->early_departure_date)) {
            return null;
        }

        $departureDate = Carbon::parse($squad->pivot->early_departure_date);

        return [
            'date' => $departureDate,
            'date_formatted' => $departureDate->format('d.m.Y'),
            'reason' => $squad->pivot->early_departure_reason,
        ];
    }

    /**
     * Получить статус ребенка в конкретном отряде
     */
    public function getStatusInSquad($squadId)
    {
        return $this->hasEarlyDepartureFromSquad($squadId) ? 'Уехал досрочно' : 'Активен';
    }

    /**
     * Получить CSS класс для статуса в конкретном отряде
     */
    public function getStatusClassInSquad($squadId)
    {
        return $this->hasEarlyDepartureFromSquad($squadId) ? 'text-red-600 bg-red-100' : 'text-green-600 bg-green-100';
    }

    /**
     * Проверяет, есть ли у ребенка хотя бы один досрочный выезд
     */
    public function hasAnyEarlyDeparture()
    {
        return $this->squads()->wherePivotNotNull('early_departure_date')->exists();
    }

    /**
     * Получить URL аватара ребенка
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && \Storage::disk('public')->exists($this->avatar)) {
            return \Storage::disk('public')->url($this->avatar);
        }
        
        // Возвращаем дефолтный аватар
        return asset('images/default-child-avatar.svg');
    }

    /**
     * Проверяет, есть ли у ребенка загруженный аватар
     */
    public function hasAvatar()
    {
        return $this->avatar && \Storage::disk('public')->exists($this->avatar);
    }

    /**
     * Удаляет аватар ребенка
     */
    public function deleteAvatar()
    {
        if ($this->avatar && \Storage::disk('public')->exists($this->avatar)) {
            \Storage::disk('public')->delete($this->avatar);
        }
        $this->avatar = null;
        $this->save();
    }
}