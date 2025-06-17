@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Заголовок в стиле HeadHunter -->
                    <div class="mb-8">
                        <!-- Верхняя строка: заголовок и кнопки -->
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                            <div class="flex-1">
                                <!-- Основной заголовок -->
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $shift->name }}</h1>
                                
                                <!-- Теги с адаптивным отображением -->
                                <div class="relative">
                                    <!-- Десктопная версия: все теги видны -->
                                    <div class="hidden md:flex flex-wrap items-center gap-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($shift->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($shift->end_date)->format('d.m.Y') }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($shift->start_date)->diffInDays(\Carbon\Carbon::parse($shift->end_date)) + 1 }} дней
                                        </span>
                                        
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>&nbsp;
                                            {{ $shift->squads->count() }} {{ $shift->squads->count() == 1 ? 'отряд' : ($shift->squads->count() <= 4 ? 'отряда' : 'отрядов') }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>&nbsp;
                                            @php
                                                $childrenCount = $shift->squads->sum(function($squad) { return $squad->children->count(); });
                                            @endphp
                                            {{ $childrenCount }} {{ $childrenCount == 1 ? 'ребенок' : ($childrenCount <= 4 ? 'ребенка' : 'детей') }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>&nbsp;
                                            {{ $shift->staff->count() }} {{ $shift->staff->count() == 1 ? 'сотрудник' : ($shift->staff->count() <= 4 ? 'сотрудника' : 'сотрудников') }}
                                        </span>
                                    </div>
                                    
                                    <!-- Мобильная версия: горизонтальный скролл -->
                                    <div class="md:hidden overflow-x-auto scrollbar-hide">
                                        <div class="flex items-center gap-3 pb-2" style="width: max-content;">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($shift->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($shift->end_date)->format('d.m.Y') }}
                                            </span>
                                            <span class="text-sm text-gray-500 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($shift->start_date)->diffInDays(\Carbon\Carbon::parse($shift->end_date)) + 1 }} дней
                                            </span>
                                            
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                {{ $shift->squads->count() }} {{ $shift->squads->count() == 1 ? 'отряд' : ($shift->squads->count() <= 4 ? 'отряда' : 'отрядов') }}
                                            </span>
                                            
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                @php
                                                    $childrenCount = $shift->squads->sum(function($squad) { return $squad->children->count(); });
                                                @endphp
                                                {{ $childrenCount }} {{ $childrenCount == 1 ? 'ребенок' : ($childrenCount <= 4 ? 'детей' : 'детей') }}
                                            </span>
                                            
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $shift->staff->count() }} {{ $shift->staff->count() == 1 ? 'сотрудник' : ($shift->staff->count() <= 4 ? 'сотрудника' : 'сотрудников') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Кнопки действий -->
                            <div class="flex items-center gap-2 lg:flex-shrink-0">
                                <a href="{{ route('admin.shifts.vouchers.index', $shift) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Путевки
                                </a>
                                
                                <a href="{{ route('admin.shifts.squads.index', $shift) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Отряды
                                </a>
                                
                                <a href="{{ route('admin.shifts.edit', $shift) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Редактировать
                                </a>
                                
                                <a href="{{ route('admin.shifts.index') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </a>
                                
                                <!-- Dropdown Menu Container -->
                                <div class="relative">
                                    <button type="button" onclick="toggleDropdown()" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Desktop Dropdown Menu -->
                                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        <div class="py-2">
                                            <button onclick="confirmDelete(); toggleDropdown();" class="w-full text-left px-4 py-3 text-sm text-red-700 hover:bg-red-50 flex items-center whitespace-nowrap">
                                                <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Удалить смену
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
                                                    <button onclick="confirmDelete(); closeMobileMenu();" class="w-full text-left py-4 px-4 text-lg text-red-700 flex items-center hover:bg-red-50 rounded-lg transition-colors whitespace-nowrap">
                                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="font-medium">Удалить смену</span>
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

                    <!-- Список сотрудников в смене -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Сотрудники на смене') }} ({{ $shift->staff->count() }})</h3>
                    
                        @if($shift->staff->isEmpty())
                            <p class="text-gray-500">{{ __('В этой смене пока нет сотрудников.') }}</p>
                        @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Имя') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Телефон') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Должность') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Статус') }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __('Действия') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($shift->staff as $staff)
                                        <tr class="hover:bg-gray-50 cursor-pointer" ondblclick="window.location.href='{{ route('admin.staff.edit', $staff) }}'" title="Двойной клик для редактирования сотрудника">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $staff->last_name }}, {{ $staff->first_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $staff->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $staff->phone }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $staff->position ? ucfirst($staff->position) : 'Не указана' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($staff->isRegistered())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Зарегистрирован
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Ожидает регистрации
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.staff.show', $staff) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Просмотр">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Скрытая форма для удаления -->
<form id="deleteForm" action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
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

// Функция подтверждения удаления
function confirmDelete() {
    if (confirm('Вы уверены, что хотите удалить эту смену? Это действие нельзя отменить.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

<!-- Стили для скрытия скроллбара -->
<style>
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar { 
    display: none;  /* Safari and Chrome */
}
</style>

@endsection 