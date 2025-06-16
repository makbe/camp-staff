<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Staff extends Authenticatable
{
    protected $fillable = [
        'avatar',
        'password',
        'last_name',
        'first_name',
        'middle_name',
        'phone',
        'email',
        'telegram',
        'position',
        'is_self_employed',
        'passport_series',
        'passport_number',
        'passport_issued_date',
        'passport_issued_by',
        'passport_code',
        'inn',
        'snils',
        'bank_name',
        'bank_account',
        'bank_bik',
        'bank_correspondent_account',
        'rating',
        'admin_comment',
        'registration_token',
        'registered_at',
    ];

    protected $hidden = [
        'password',
        'registration_token',
    ];

    protected $casts = [
        'is_self_employed' => 'boolean',
        'passport_issued_date' => 'date',
        'registered_at' => 'datetime',
    ];

    /**
     * Получить смены сотрудника
     */
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'shift_staff')->withTimestamps();
    }
    
    /**
     * Получить первую смену сотрудника (для обратной совместимости)
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Получить отряды, где сотрудник является вожатой
     */
    public function squads()
    {
        return $this->hasMany(Squad::class, 'counselor_id');
    }

    /**
     * Получить полное имя сотрудника
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name);
    }

    /**
     * Проверить пароль
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Установить пароль с хешированием
     */
    public function setPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Сгенерировать токен для регистрации
     */
    public static function generateRegistrationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Завершить регистрацию
     */
    public function completeRegistration(): void
    {
        $this->registered_at = now();
        $this->save();
    }

    /**
     * Проверить, зарегистрирован ли сотрудник
     */
    public function isRegistered(): bool
    {
        return !is_null($this->registered_at);
    }

    /**
     * Получить доступные должности
     */
    public static function getPositions(): array
    {
        return [
            'вожатый',
            'методист',
            'декоратор',
            'фотограф',
            'видеограф',
            'хореограф',
            'старший воспитатель',
            'психолог',
            'другое'
        ];
    }
}
