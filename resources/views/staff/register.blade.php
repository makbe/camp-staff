@extends('layouts.mobile')

@section('title', 'Регистрация сотрудника')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-lg font-semibold">Регистрация сотрудника</h1>
</div>
@endsection

@section('content')
<div class="px-4 py-6">
    <form action="{{ route('staff.register.post', $token) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Шаг 1: Основная информация -->
        <div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
            <h2 class="text-base font-medium text-gray-900">Основная информация</h2>
            
            <!-- Выбор смены -->
            <div>
                <label for="shift_id" class="block text-sm font-medium text-gray-700 mb-1">Смена <span class="text-red-500">*</span></label>
                <select name="shift_id" id="shift_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Выберите смену</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }})
                        </option>
                    @endforeach
                </select>
                @error('shift_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Фото аватара -->
            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Фото профиля</label>
                <input type="file" name="avatar" id="avatar" accept="image/*" capture="user" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Пароль -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Подтверждение пароля -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Подтвердите пароль <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        
        <!-- Шаг 2: Личные данные -->
        <div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
            <h2 class="text-base font-medium text-gray-900">Личные данные</h2>
            
            <!-- Фамилия -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Имя -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Отчество -->
            <div>
                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Отчество</label>
                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('middle_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Телефон -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="+7XXXXXXXXXX" pattern="\+7[0-9]{10}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $staff->email ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Telegram -->
            <div>
                <label for="telegram" class="block text-sm font-medium text-gray-700 mb-1">Ник в Telegram</label>
                <input type="text" name="telegram" id="telegram" value="{{ old('telegram') }}" placeholder="@username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('telegram')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Должность -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Должность <span class="text-red-500">*</span></label>
                <select name="position" id="position" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Выберите должность</option>
                    @foreach(\App\Models\Staff::getPositions() as $position)
                        <option value="{{ $position }}" {{ old('position') == $position ? 'selected' : '' }}>{{ ucfirst($position) }}</option>
                    @endforeach
                </select>
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Самозанятый -->
            <div class="flex items-center">
                <input type="checkbox" name="is_self_employed" id="is_self_employed" value="1" {{ old('is_self_employed') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_self_employed" class="ml-2 block text-sm text-gray-900">
                    Я являюсь самозанятым
                </label>
            </div>
        </div>
        
        <!-- Шаг 3: Паспортные данные -->
        <div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
            <h2 class="text-base font-medium text-gray-900">Паспортные данные</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <!-- Серия паспорта -->
                <div>
                    <label for="passport_series" class="block text-sm font-medium text-gray-700 mb-1">Серия</label>
                    <input type="text" name="passport_series" id="passport_series" value="{{ old('passport_series') }}" maxlength="4" placeholder="0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('passport_series')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Номер паспорта -->
                <div>
                    <label for="passport_number" class="block text-sm font-medium text-gray-700 mb-1">Номер</label>
                    <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number') }}" maxlength="6" placeholder="000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('passport_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Дата выдачи -->
            <div>
                <label for="passport_issued_date" class="block text-sm font-medium text-gray-700 mb-1">Дата выдачи</label>
                <input type="date" name="passport_issued_date" id="passport_issued_date" value="{{ old('passport_issued_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('passport_issued_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Кем выдан -->
            <div>
                <label for="passport_issued_by" class="block text-sm font-medium text-gray-700 mb-1">Кем выдан</label>
                <textarea name="passport_issued_by" id="passport_issued_by" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('passport_issued_by') }}</textarea>
                @error('passport_issued_by')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Код подразделения -->
            <div>
                <label for="passport_code" class="block text-sm font-medium text-gray-700 mb-1">Код подразделения</label>
                <input type="text" name="passport_code" id="passport_code" value="{{ old('passport_code') }}" placeholder="000-000" maxlength="7" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('passport_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- ИНН -->
            <div>
                <label for="inn" class="block text-sm font-medium text-gray-700 mb-1">ИНН</label>
                <input type="text" name="inn" id="inn" value="{{ old('inn') }}" maxlength="12" placeholder="000000000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('inn')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- СНИЛС -->
            <div>
                <label for="snils" class="block text-sm font-medium text-gray-700 mb-1">СНИЛС</label>
                <input type="text" name="snils" id="snils" value="{{ old('snils') }}" maxlength="11" placeholder="00000000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('snils')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Шаг 4: Банковские реквизиты -->
        <div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
            <h2 class="text-base font-medium text-gray-900">Банковские реквизиты</h2>
            
            <!-- Название банка -->
            <div>
                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Название банка</label>
                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bank_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Номер счета -->
            <div>
                <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-1">Номер счета</label>
                <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account') }}" maxlength="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bank_account')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- БИК -->
            <div>
                <label for="bank_bik" class="block text-sm font-medium text-gray-700 mb-1">БИК</label>
                <input type="text" name="bank_bik" id="bank_bik" value="{{ old('bank_bik') }}" maxlength="9" placeholder="000000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bank_bik')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Корреспондентский счет -->
            <div>
                <label for="bank_correspondent_account" class="block text-sm font-medium text-gray-700 mb-1">Корреспондентский счет</label>
                <input type="text" name="bank_correspondent_account" id="bank_correspondent_account" value="{{ old('bank_correspondent_account') }}" maxlength="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bank_correspondent_account')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Кнопка отправки -->
        <div class="px-4 pb-6">
            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mobile-button">
                Зарегистрироваться
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Маска для телефона
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('7')) {
            value = value.substring(1);
        }
        if (value.length > 0) {
            e.target.value = '+7' + value.substring(0, 10);
        }
    });
    
    // Маска для паспорта
    document.getElementById('passport_code').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 3) {
            e.target.value = value.substring(0, 3) + '-' + value.substring(3, 6);
        } else {
            e.target.value = value;
        }
    });
</script>
@endpush 