@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Дети</h1>
                    <div class="flex space-x-3">
                        <button onclick="document.getElementById('csvUploadModal').classList.remove('hidden')" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Загрузить CSV
                        </button>
                        <a href="{{ route('admin.children.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Добавить ребенка
                        </a>
                    </div>
                </div>

                <!-- Модальное окно для загрузки CSV -->
                <div id="csvUploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="flex items-center justify-center min-h-screen px-4 py-6">
                        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full mx-auto">
                            <!-- Заголовок -->
                            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Загрузить детей из CSV</h3>
                                <button onclick="document.getElementById('csvUploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Содержимое -->
                            <div class="p-6">
                                <form action="{{ route('admin.children.import-csv') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    
                                    <!-- Поле выбора файла -->
                                    <div>
                                        <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">
                                            Выберите CSV файл
                                        </label>
                                        <input type="file" 
                                               name="csv_file" 
                                               id="csv_file" 
                                               accept=".csv" 
                                               required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </div>
                                    
                                    <!-- Информация о формате -->
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <h4 class="text-sm font-medium text-blue-900 mb-2">Формат файла</h4>
                                                <ul class="text-xs text-blue-800 space-y-1">
                                                    <li>• Разделитель: точка с запятой (;)</li>
                                                    <li>• 1-я колонка: ФИО</li>
                                                    <li>• 2-я колонка: Дата рождения (ДД.ММ.ГГГГ)</li>
                                                    <li>• Без заголовков</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Кнопки -->
                                    <div class="flex justify-end space-x-3 pt-4">
                                        <button type="button" 
                                                onclick="document.getElementById('csvUploadModal').classList.add('hidden')"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                            Отмена
                                        </button>
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            Загрузить
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 rounded-md bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">
                                    {{ session('warning') }}
                                </p>
                                @if (session('import_errors'))
                                    <div class="mt-2">
                                        <details class="cursor-pointer">
                                            <summary class="text-sm font-medium text-yellow-800">Показать ошибки ({{ count(session('import_errors')) }})</summary>
                                            <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                                @foreach (session('import_errors') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Ошибки валидации:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Статистика -->
                <div class="mb-6 flex flex-wrap gap-2 sm:gap-4">
                    <div class="flex-1 min-w-0 bg-blue-50 p-3 sm:p-4 rounded-lg text-center">
                        <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ $totalChildren }}</div>
                        <div class="text-xs sm:text-sm text-blue-600">Всего детей</div>
                    </div>
                    <div class="flex-1 min-w-0 bg-green-50 p-3 sm:p-4 rounded-lg text-center">
                        <div class="text-xl sm:text-2xl font-bold text-green-600">{{ $childrenInSquads }}</div>
                        <div class="text-xs sm:text-sm text-green-600">В отрядах</div>
                    </div>
                    <div class="flex-1 min-w-0 bg-yellow-50 p-3 sm:p-4 rounded-lg text-center">
                        <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $childrenWithoutSquads }}</div>
                        <div class="text-xs sm:text-sm text-yellow-600">Без отряда</div>
                    </div>
                </div>

                <!-- Фильтры -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="GET" action="{{ route('admin.children.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Поиск по имени -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Поиск по ФИО</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}" 
                                       placeholder="Введите ФИО..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Фильтр по смене -->
                            <div>
                                <label for="shift_id" class="block text-sm font-medium text-gray-700 mb-1">Смена</label>
                                <select name="shift_id" id="shift_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Все смены</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Фильтр по отряду -->
                            <div>
                                <label for="squad_id" class="block text-sm font-medium text-gray-700 mb-1">Отряд</label>
                                <select name="squad_id" id="squad_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Все отряды</option>
                                    @foreach($squads as $squad)
                                        <option value="{{ $squad->id }}" {{ request('squad_id') == $squad->id ? 'selected' : '' }}>
                                            {{ $squad->name }} ({{ $squad->shift->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Возраст -->
                            <div class="flex space-x-2">
                                <div class="flex-1">
                                    <label for="age_from" class="block text-sm font-medium text-gray-700 mb-1">Возраст от</label>
                                    <input type="number" 
                                           name="age_from" 
                                           id="age_from"
                                           value="{{ request('age_from') }}" 
                                           min="0" 
                                           max="18"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="flex-1">
                                    <label for="age_to" class="block text-sm font-medium text-gray-700 mb-1">до</label>
                                    <input type="number" 
                                           name="age_to" 
                                           id="age_to"
                                           value="{{ request('age_to') }}" 
                                           min="0" 
                                           max="18"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Применить фильтры
                            </button>
                            <a href="{{ route('admin.children.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Сбросить
                            </a>
                        </div>
                    </form>
                </div>

                @if($children->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Фото
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ФИО
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Возраст
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Дата рождения
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Отрядов
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($children as $child)
                                    <tr class="hover:bg-gray-50 cursor-pointer" 
                                        ondblclick="window.location.href='{{ route('admin.children.edit', $child) }}'"
                                        title="Двойной клик для редактирования">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ $child->avatar_url }}" 
                                                 alt="Фото {{ $child->full_name }}" 
                                                 class="h-10 w-10 rounded-full object-cover">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $child->full_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $child->age_text }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $child->birth_date_formatted }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($child->squads->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($child->squads as $squad)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $squad->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Без отряда
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.children.show', $child) }}" 
                                                   class="text-blue-600 hover:text-blue-900" 
                                                   title="Просмотр">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.children.edit', $child) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900" 
                                                   title="Редактировать">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                @if($child->squads->count() > 0)
                                                    <!-- Неактивная кнопка удаления для детей в отрядах -->
                                                    <button type="button" 
                                                            class="text-gray-400 cursor-not-allowed" 
                                                            disabled
                                                            title="Нельзя удалить ребенка, состоящего в отрядах. Сначала исключите из всех отрядов.">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <!-- Активная кнопка удаления для детей без отрядов -->
                                                    <form method="POST" action="{{ route('admin.children.destroy', $child) }}" class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить ребенка {{ $child->full_name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900" 
                                                                title="Удалить">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    <div class="mt-6">
                        {{ $children->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Нет детей</h3>
                        <p class="mt-1 text-sm text-gray-500">Начните с добавления первого ребенка.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.children.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Добавить ребенка
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Закрытие модального окна при клике вне его
document.getElementById('csvUploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// Закрытие модального окна при нажатии Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('csvUploadModal').classList.add('hidden');
    }
});
</script>

@endsection 