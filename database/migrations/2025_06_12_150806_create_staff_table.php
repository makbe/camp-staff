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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade'); // Связь со сменой
            $table->string('avatar')->nullable(); // Фото аватара
            $table->string('password'); // Пароль к личному кабинету
            $table->string('last_name'); // Фамилия
            $table->string('first_name'); // Имя
            $table->string('middle_name')->nullable(); // Отчество
            $table->string('phone'); // Номер телефона +7ХХХХХХХХХХ
            $table->string('email')->unique(); // Email
            $table->string('telegram')->nullable(); // Ник в телеграмме
            $table->enum('position', [
                'вожатый', 
                'методист', 
                'декоратор', 
                'фотограф', 
                'видеограф', 
                'хореограф', 
                'старший воспитатель', 
                'психолог', 
                'другое'
            ]); // Должность
            $table->boolean('is_self_employed')->default(false); // Признак самозанятый
            $table->string('passport_series')->nullable(); // Серия паспорта
            $table->string('passport_number')->nullable(); // Номер паспорта
            $table->date('passport_issued_date')->nullable(); // Дата выдачи паспорта
            $table->string('passport_issued_by')->nullable(); // Кем выдан паспорт
            $table->string('passport_code')->nullable(); // Код подразделения
            $table->string('inn')->nullable(); // Номер ИНН
            $table->string('snils')->nullable(); // Номер СНИЛС
            $table->string('bank_name')->nullable(); // Название банка
            $table->string('bank_account')->nullable(); // Номер счета
            $table->string('bank_bik')->nullable(); // БИК банка
            $table->string('bank_correspondent_account')->nullable(); // Корреспондентский счет
            
            // Поля для администратора
            $table->integer('rating')->nullable(); // Рейтинг
            $table->text('admin_comment')->nullable(); // Комментарий администратора
            
            $table->string('registration_token')->unique(); // Токен для регистрации
            $table->timestamp('registered_at')->nullable(); // Время регистрации
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
