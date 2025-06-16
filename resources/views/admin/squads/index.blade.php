@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- HeadHunter-style Header -->
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">
                            Отряды смены "{{ $shift->name }}"
                        </h1>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $squads->count() }} {{ $squads->count() == 1 ? 'отряд' : ($squads->count() <= 4 ? 'отряда' : 'отрядов') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-row items-center gap-2 lg:flex-shrink-0">
                        <a href="{{ route('admin.shifts.squads.create', $shift) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-flex items-center justify-center transition-colors text-sm font-medium whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Создать отряд
                        </a>
                        <a href="{{ route('admin.shifts.show', $shift) }}" 
                           class="px-3 py-2 border border-gray-300 bg-white text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <!-- Dropdown Menu -->
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                            
                            <!-- Desktop Dropdown Menu -->
                            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-2">
                                    <button onclick="openBulkCreateModal(); toggleDropdown();" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center whitespace-nowrap">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Массовое создание
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Mobile Bottom Sheet -->
                            <div id="mobileBottomSheet" class="hidden fixed inset-0 z-[9999]">
                                <!-- Darker overlay like HeadHunter -->
                                <div class="fixed inset-0 bg-black bg-opacity-60" onclick="closeMobileMenu()"></div>
                                <!-- Bottom sheet with better styling -->
                                <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl shadow-2xl">
                                    <div class="p-6">
                                        <!-- Handle bar -->
                                        <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto mb-6"></div>
                                        
                                        <!-- Menu items -->
                                        <div class="space-y-1">
                                            <button onclick="openBulkCreateModal(); closeMobileMenu();" class="w-full text-left py-4 px-4 text-lg text-gray-900 flex items-center hover:bg-gray-50 rounded-lg transition-colors whitespace-nowrap">
                                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                    </svg>
                                                </div>
                                                <span class="font-medium">Массовое создание</span>
                                            </button>
                                        </div>
                                        
                                        <!-- Cancel button -->
                                        <div class="mt-8 pt-4 border-t border-gray-100">
                                            <button onclick="closeMobileMenu()" class="w-full text-center py-4 text-lg text-gray-500 font-medium hover:text-gray-700 transition-colors">
                                                Отмена
                                            </button>
                                        </div>
                                    </div>
                                </div>
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

                @if($squads->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Нет отрядов</h3>
                        <p class="mt-1 text-sm text-gray-500">Создайте первый отряд для этой смены.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.shifts.squads.create', $shift) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Создать отряд
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($squads as $squad)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer" ondblclick="window.location.href='{{ route('admin.shifts.squads.edit', [$shift, $squad]) }}'" title="Двойной клик для редактирования">
                                <!-- Заголовок отряда -->
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $squad->name }}</h3>
                                            @if($squad->age_group)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                    {{ $squad->age_group }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Информация об отряде -->
                                <div class="p-4">
                                    <!-- Вожатая -->
                                    <div class="mb-3">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Вожатая:</dt>
                                        @if($squad->counselor)
                                            <dd class="text-sm text-gray-900 flex items-center">
                                                <div class="flex-shrink-0 h-6 w-6 mr-2">
                                                    @if($squad->counselor->avatar)
                                                        <img class="h-6 w-6 rounded-full" src="{{ Storage::url($squad->counselor->avatar) }}" alt="">
                                                    @else
                                                        <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center">
                                                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                {{ $squad->counselor->full_name ?: $squad->counselor->email }}
                                            </dd>
                                        @else
                                            <dd class="text-sm text-gray-400">Не назначена</dd>
                                        @endif
                                    </div>

                                    <!-- Заполненность -->
                                    <div class="mb-3">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Количество детей:</dt>
                                        <div class="text-sm text-gray-600">
                                            {{ $squad->getCurrentChildrenCount() }} детей
                                        </div>
                                    </div>

                                    @if($squad->description)
                                        <div class="mb-3">
                                            <dt class="text-sm font-medium text-gray-500 mb-1">Описание:</dt>
                                            <dd class="text-sm text-gray-700">{{ Str::limit($squad->description, 80) }}</dd>
                                        </div>
                                    @endif
                                </div>

                                <!-- Действия -->
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2">
                                    <a href="{{ route('admin.shifts.squads.show', [$shift, $squad]) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Просмотр">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.shifts.squads.edit', [$shift, $squad]) }}" class="text-yellow-600 hover:text-yellow-900 p-1" title="Редактировать">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.shifts.squads.destroy', [$shift, $squad]) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить отряд &quot;{{ $squad->name }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Удалить">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для массового создания отрядов -->
<div id="bulkCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto">
            <!-- Заголовок -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Массовое создание отрядов</h3>
                <button onclick="closeBulkCreateModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Содержимое -->
            <div class="p-6">
                <form action="{{ route('admin.shifts.squads.bulk-create', $shift) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Поле количества -->
                    <div>
                        <label for="count" class="block text-sm font-medium text-gray-700 mb-2">
                            Количество отрядов
                        </label>
                        <input type="number" 
                               id="count" 
                               name="count" 
                               min="1" 
                               max="20" 
                               value="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <p class="mt-2 text-xs text-gray-500">
                            Отряды будут созданы с названиями "Отряд №X" (от 1 до 20)
                        </p>
                    </div>
                    
                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" 
                                onclick="closeBulkCreateModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Отмена
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            Создать отряды
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openBulkCreateModal() {
    document.getElementById('bulkCreateModal').classList.remove('hidden');
}

function closeBulkCreateModal() {
    document.getElementById('bulkCreateModal').classList.add('hidden');
}

// Закрытие модального окна при клике вне его
document.getElementById('bulkCreateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkCreateModal();
    }
});

// Закрытие модального окна при нажатии Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBulkCreateModal();
    }
});

function toggleDropdown() {
    const dropdownMenu = document.getElementById('dropdownMenu');
    const mobileBottomSheet = document.getElementById('mobileBottomSheet');
    const isMobile = window.innerWidth <= 1024; // lg breakpoint - более широкий диапазон для мобильных
    
    // Сначала скрываем оба меню
    dropdownMenu.classList.add('hidden');
    mobileBottomSheet.classList.add('hidden');
    
    if (isMobile) {
        // Mobile: show bottom sheet
        mobileBottomSheet.classList.remove('hidden');
        // Добавляем класс для предотвращения скролла
        document.body.style.overflow = 'hidden';
    } else {
        // Desktop: show dropdown
        dropdownMenu.classList.remove('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdownContainer = event.target.closest('.relative');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const mobileBottomSheet = document.getElementById('mobileBottomSheet');
    
    if (!dropdownContainer) {
        dropdownMenu.classList.add('hidden');
        mobileBottomSheet.classList.add('hidden');
        // Восстанавливаем скролл
        document.body.style.overflow = '';
    }
});

// Функция для закрытия мобильного меню
function closeMobileMenu() {
    const mobileBottomSheet = document.getElementById('mobileBottomSheet');
    mobileBottomSheet.classList.add('hidden');
    document.body.style.overflow = '';
}
</script>

@endsection 