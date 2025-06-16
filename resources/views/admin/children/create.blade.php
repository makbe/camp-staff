@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Добавить ребенка</h1>
                    <a href="{{ route('admin.children.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Назад к списку
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.children.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <!-- ФИО -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                            ФИО ребенка <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="full_name" 
                               id="full_name" 
                               value="{{ old('full_name') }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('full_name') border-red-300 @enderror"
                               placeholder="Введите полное ФИО ребенка">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Аватар -->
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                            Фотография ребенка
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img id="avatar-preview" 
                                     src="{{ asset('images/default-child-avatar.svg') }}" 
                                     alt="Предварительный просмотр" 
                                     class="h-20 w-20 rounded-full object-cover border-2 border-gray-300">
                            </div>
                            <div class="flex-1">
                                <input type="file" 
                                       name="avatar" 
                                       id="avatar" 
                                       accept="image/*"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('avatar') border-red-300 @enderror">
                                <p class="mt-1 text-xs text-gray-500">
                                    Поддерживаемые форматы: JPEG, PNG, JPG, GIF. Максимальный размер: 2MB
                                </p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Дата рождения -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Дата рождения <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="birth_date" 
                               id="birth_date" 
                               value="{{ old('birth_date') }}"
                               required
                               max="{{ date('Y-m-d') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('birth_date') border-red-300 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Отряды -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Отряды
                        </label>
                        <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($squads as $squad)
                                <div class="flex items-center">
                                    <input id="squad_{{ $squad->id }}" 
                                           name="squads[]" 
                                           type="checkbox" 
                                           value="{{ $squad->id }}"
                                           {{ in_array($squad->id, old('squads', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="squad_{{ $squad->id }}" class="ml-3 text-sm">
                                        <span class="font-medium text-gray-900">{{ $squad->name }}</span>
                                        <span class="text-gray-500">({{ $squad->shift->name }})</span>
                                        @if($squad->age_group)
                                            <span class="text-xs text-blue-600">- {{ $squad->age_group }}</span>
                                        @endif
                                        <span class="text-xs text-gray-400">- {{ $squad->getCurrentChildrenCount() }} детей</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('squads')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('squads.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Ребенка можно добавить в несколько отрядов (разные смены)
                        </p>
                        
                        <!-- Кнопки выбора всех/снятия выбора -->
                        <div class="mt-2 flex space-x-2">
                            <button type="button" 
                                    onclick="selectAllSquads()" 
                                    class="text-xs text-blue-600 hover:text-blue-800">
                                Выбрать все доступные
                            </button>
                            <button type="button" 
                                    onclick="deselectAllSquads()" 
                                    class="text-xs text-gray-600 hover:text-gray-800">
                                Снять выбор
                            </button>
                        </div>
                    </div>

                    <!-- Заметки -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Дополнительные заметки
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-300 @enderror"
                                  placeholder="Любая дополнительная информация о ребенке...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-between">
                        <a href="{{ route('admin.children.index') }}" 
                           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Добавить ребенка
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Автоматически вычисляем возраст при изменении даты рождения
document.getElementById('birth_date').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
    
    if (age >= 0 && age <= 100) {
        // Можно показать возраст пользователю
        console.log('Возраст:', age);
    }
});

// Предварительный просмотр аватара
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Функции для управления выбором отрядов
function selectAllSquads() {
    const checkboxes = document.querySelectorAll('input[name="squads[]"]:not(:disabled)');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllSquads() {
    const checkboxes = document.querySelectorAll('input[name="squads[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endsection 