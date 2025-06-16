@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Заголовок в стиле HeadHunter -->
                <div class="mb-8">
                    <!-- Верхняя строка: заголовок и кнопки -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                        <div class="flex-1">
                            <!-- Основной заголовок с аватаром -->
                            <div class="flex items-start space-x-4 mb-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ $child->avatar_url }}" 
                                         alt="Фото {{ $child->full_name }}" 
                                         class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-lg">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $child->full_name }}</h1>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v10a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                                        </svg>
                                        Дата рождения: {{ $child->birth_date_formatted }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Теги и информация -->
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v10a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                                    </svg>
                                    {{ $child->age_text }}
                                </span>
                                
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    {{ $child->squads->count() }} 
                                    {{ $child->squads->count() == 1 ? 'отряд' : ($child->squads->count() <= 4 ? 'отряда' : 'отрядов') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Кнопки действий -->
                        <div class="flex items-center gap-2 lg:flex-shrink-0">
                            <a href="{{ route('admin.children.edit', $child) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Редактировать
                            </a>
                            
                            <a href="{{ route('admin.children.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Назад к списку
                            </a>
                            
                            <!-- Выпадающее меню с тремя точками -->
                            <div class="relative inline-block text-left">
                                <button type="button" 
                                        onclick="toggleDropdown()"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Выпадающее меню -->
                                <div id="dropdownMenu" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1">
                                        @if($child->squads->count() > 0)
                                            <button type="button" 
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-400 cursor-not-allowed"
                                                    disabled
                                                    title="Нельзя удалить ребенка, который состоит в отрядах: {{ $child->squads->pluck('name')->join(', ') }}">
                                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Удалить (недоступно)
                                            </button>
                                        @else
                                            <form action="{{ route('admin.children.destroy', $child) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить ребенка &quot;{{ $child->full_name }}&quot;? Это действие нельзя отменить.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-900 transition-colors">
                                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Удалить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Отряды -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Отряды</h3>
                            <span class="text-sm text-gray-500">{{ $child->squads->count() }} {{ $child->squads->count() == 1 ? 'отряд' : 'отрядов' }}</span>
                        </div>

                        @if($child->squads->count() > 0)
                            <div class="space-y-3">
                                @foreach($child->squads as $squad)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <!-- Основная информация об отряде -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-medium">
                                                    {{ substr($squad->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $squad->name }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $squad->shift->name ?? 'Смена не указана' }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Активный
                                            </span>
                                        </div>

                                        <!-- Информация о вожатой -->
                                        @if($squad->counselor)
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        @if($squad->counselor->avatar)
                                                            <img src="{{ Storage::url($squad->counselor->avatar) }}" 
                                                                 alt="{{ $squad->counselor->full_name ?: $squad->counselor->email }}" 
                                                                 class="w-8 h-8 rounded-full object-cover">
                                                        @else
                                                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                                                {{ substr($squad->counselor->full_name ?: $squad->counselor->email, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $squad->counselor->full_name ?: $squad->counselor->email }}</p>
                                                            <p class="text-xs text-gray-500">Вожатая отряда</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        @if($squad->counselor->phone)
                                                            <a href="tel:{{ $squad->counselor->phone }}" 
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded hover:bg-green-200 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                                                </svg>
                                                                {{ $squad->counselor->phone }}
                                                            </a>
                                                        @endif
                                                        @if($squad->counselor->telegram)
                                                            <a href="https://t.me/{{ ltrim($squad->counselor->telegram, '@') }}" 
                                                               target="_blank"
                                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M12 0C5.374 0 0 5.373 0 12s5.374 12 12 12 12-5.373 12-12S18.626 0 12 0zm5.568 8.16l-1.61 7.59c-.12.54-.44.67-.89.42l-2.46-1.81-1.19 1.14c-.13.13-.24.24-.49.24l.17-2.43 4.47-4.03c.19-.17-.04-.27-.3-.1L9.28 13.47l-2.38-.75c-.52-.16-.53-.52.11-.77l9.28-3.58c.43-.16.81.11.67.73z"/>
                                                                </svg>
                                                                {{ $squad->counselor->telegram }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-amber-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm text-amber-700">Вожатая не назначена</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Нет отрядов</h3>
                                <p class="mt-1 text-sm text-gray-500">Ребенок пока не состоит ни в одном отряде</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Заметки -->
                @if($child->notes)
                    <div class="mt-6 bg-yellow-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Дополнительные заметки</h2>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $child->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('hidden');
}

// Закрытие выпадающего меню при клике вне его
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownMenu');
    const button = event.target.closest('button[onclick="toggleDropdown()"]');
    
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

// Закрытие выпадающего меню при нажатии Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('dropdownMenu').classList.add('hidden');
    }
});
</script>

@endsection 