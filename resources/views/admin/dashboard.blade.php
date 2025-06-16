@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Контейнер с правильными отступами как на HH -->
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 lg:text-3xl">
                Панель администратора
            </h1>
            <p class="text-gray-600 text-base lg:text-lg">
                Управление лагерем и персоналом
            </p>
        </div>

        <!-- Основная статистика -->
        <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Сотрудники -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-2xl font-bold text-gray-900">{{ $totalStaff }}</div>
                        <div class="text-sm text-gray-600 truncate">Сотрудники</div>
                        <div class="text-xs text-green-600 font-medium">{{ $registeredStaff }} зарегистрировано</div>
                    </div>
                </div>
            </div>

            <!-- Смены -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-2xl font-bold text-gray-900">{{ $totalShifts }}</div>
                        <div class="text-sm text-gray-600 truncate">Смены</div>
                        <div class="text-xs text-purple-600 font-medium">{{ $totalSquads }} отрядов</div>
                    </div>
                </div>
            </div>

            <!-- Отряды -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-2xl font-bold text-gray-900">{{ $totalSquads }}</div>
                        <div class="text-sm text-gray-600 truncate">Отряды</div>
                        <div class="text-xs text-indigo-600 font-medium">{{ $squadsWithCounselors }} с вожатыми</div>
                    </div>
                </div>
            </div>

            <!-- Дети -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-2xl font-bold text-gray-900">{{ $totalChildren }}</div>
                        <div class="text-sm text-gray-600 truncate">Дети</div>
                        <div class="text-xs text-orange-600 font-medium">{{ $childrenInSquads }} в отрядах</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дополнительная статистика -->
        <div class="grid grid-cols-2 gap-4 mb-8 lg:grid-cols-4">
            <!-- Ожидают регистрации -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-lg font-bold text-yellow-600">{{ $pendingStaff }}</div>
                        <div class="text-xs text-gray-600 truncate">Ожидают регистрации</div>
                    </div>
                </div>
            </div>

            <!-- Зарегистрированы -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-lg font-bold text-green-600">{{ $registeredStaff }}</div>
                        <div class="text-xs text-gray-600 truncate">Зарегистрированы</div>
                    </div>
                </div>
            </div>

            <!-- Дети без отрядов -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-lg font-bold text-red-600">{{ $totalChildren - $childrenInSquads }}</div>
                        <div class="text-xs text-gray-600 truncate">Дети без отрядов</div>
                    </div>
                </div>
            </div>

            <!-- Отряды без вожатых -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-2.121-2.121m0 0L12 15l-2.121-2.121M15 12l-2.121-2.121M12 9l-2.121-2.121"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-lg font-bold text-gray-600">{{ $totalSquads - $squadsWithCounselors }}</div>
                        <div class="text-xs text-gray-600 truncate">Отряды без вожатых</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Быстрые действия -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Управление сотрудниками -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Сотрудники</h3>
                </div>
                <p class="text-gray-600 mb-6 text-sm">Управление персоналом лагеря</p>
                <div class="space-y-3">
                    <a href="{{ route('admin.staff.index') }}" 
                       class="block w-full text-center bg-blue-600 text-white py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                        Список сотрудников
                    </a>
                    <a href="{{ route('admin.staff.create') }}" 
                       class="block w-full text-center border border-blue-600 text-blue-600 py-2.5 px-4 rounded-lg hover:bg-blue-50 transition-colors font-medium text-sm">
                        Добавить сотрудника
                    </a>
                </div>
            </div>
            
            <!-- Управление сменами -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Смены</h3>
                </div>
                <p class="text-gray-600 mb-6 text-sm">Планирование и организация смен</p>
                <div class="space-y-3">
                    <a href="{{ route('admin.shifts.index') }}" 
                       class="block w-full text-center bg-purple-600 text-white py-2.5 px-4 rounded-lg hover:bg-purple-700 transition-colors font-medium text-sm">
                        Список смен
                    </a>
                    <a href="{{ route('admin.shifts.create') }}" 
                       class="block w-full text-center border border-purple-600 text-purple-600 py-2.5 px-4 rounded-lg hover:bg-purple-50 transition-colors font-medium text-sm">
                        Создать смену
                    </a>
                </div>
            </div>

            <!-- Управление детьми -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow md:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Дети</h3>
                </div>
                <p class="text-gray-600 mb-6 text-sm">Управление участниками лагеря</p>
                <div class="space-y-3">
                    <a href="{{ route('admin.children.index') }}" 
                       class="block w-full text-center bg-orange-600 text-white py-2.5 px-4 rounded-lg hover:bg-orange-700 transition-colors font-medium text-sm">
                        Список детей
                    </a>
                    <a href="{{ route('admin.children.create') }}" 
                       class="block w-full text-center border border-orange-600 text-orange-600 py-2.5 px-4 rounded-lg hover:bg-orange-50 transition-colors font-medium text-sm">
                        Добавить ребенка
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 