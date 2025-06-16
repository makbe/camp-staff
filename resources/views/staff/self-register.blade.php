@extends('layouts.mobile')

@section('title', 'Регистрация сотрудника')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-xl font-bold text-white">Регистрация сотрудника</h1>
                <p class="text-blue-100 text-sm mt-1">Заполните все поля для регистрации</p>
            </div>

            <form action="{{ route('staff.self-register') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Выбор смены -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Выберите смену *</label>
                    <select name="shift_id" required
                            class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Выберите смену --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }} ({{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Аватар -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Фото</label>
                    <input type="file" name="avatar" accept="image/*"
                           class="mobile-input block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <!-- Основная информация -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-900">Основная информация</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Фамилия *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Имя *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Отчество</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Должность *</label>
                        <select name="position" required
                                class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Выберите должность --</option>
                            <option value="вожатый" {{ old('position') == 'вожатый' ? 'selected' : '' }}>Вожатый</option>
                            <option value="администратор" {{ old('position') == 'администратор' ? 'selected' : '' }}>Администратор</option>
                            <option value="спорт инструктор" {{ old('position') == 'спорт инструктор' ? 'selected' : '' }}>Спорт инструктор</option>
                            <option value="медработник" {{ old('position') == 'медработник' ? 'selected' : '' }}>Медработник</option>
                            <option value="диджей" {{ old('position') == 'диджей' ? 'selected' : '' }}>Диджей</option>
                            <option value="фотограф" {{ old('position') == 'фотограф' ? 'selected' : '' }}>Фотограф</option>
                            <option value="кухонный работник" {{ old('position') == 'кухонный работник' ? 'selected' : '' }}>Кухонный работник</option>
                            <option value="другое" {{ old('position') == 'другое' ? 'selected' : '' }}>Другое</option>
                        </select>
                    </div>
                </div>

                <!-- Контактная информация -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-900">Контактная информация</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Телефон *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               placeholder="+7 (999) 123-45-67"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telegram</label>
                        <input type="text" name="telegram" value="{{ old('telegram') }}"
                               placeholder="@username"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Паспортные данные -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-900">Паспортные данные</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Серия *</label>
                            <input type="text" name="passport_series" value="{{ old('passport_series') }}" required
                                   placeholder="1234"
                                   class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Номер *</label>
                            <input type="text" name="passport_number" value="{{ old('passport_number') }}" required
                                   placeholder="567890"
                                   class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Дата выдачи *</label>
                        <input type="date" name="passport_issued_date" value="{{ old('passport_issued_date') }}" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Кем выдан *</label>
                        <input type="text" name="passport_issued_by" value="{{ old('passport_issued_by') }}" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Код подразделения *</label>
                        <input type="text" name="passport_code" value="{{ old('passport_code') }}" required
                               placeholder="123-456"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- ИНН и СНИЛС -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-900">Документы</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ИНН *</label>
                        <input type="text" name="inn" value="{{ old('inn') }}" required
                               placeholder="123456789012"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">СНИЛС *</label>
                        <input type="text" name="snils" value="{{ old('snils') }}" required
                               placeholder="123-456-789 00"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_self_employed" value="1" {{ old('is_self_employed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Я самозанятый</span>
                        </label>
                    </div>
                </div>

                <!-- Банковские реквизиты -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-900">Банковские реквизиты</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Название банка *</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                               placeholder="Сбербанк"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Расчетный счет *</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}" required
                               placeholder="40817810123456789012"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">БИК банка *</label>
                        <input type="text" name="bank_bik" value="{{ old('bank_bik') }}" required
                               placeholder="044525225"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Корреспондентский счет *</label>
                        <input type="text" name="bank_correspondent_account" value="{{ old('bank_correspondent_account') }}" required
                               placeholder="30101810400000000225"
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Пароль -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-900">Пароль для входа</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Пароль *</label>
                        <input type="password" name="password" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Подтвердите пароль *</label>
                        <input type="password" name="password_confirmation" required
                               class="mobile-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mobile-button">
                    Зарегистрироваться
                </button>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Уже зарегистрированы? 
                        <a href="{{ route('unified.login') }}" class="text-blue-600 hover:text-blue-800">Войти</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 