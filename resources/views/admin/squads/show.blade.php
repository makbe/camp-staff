@extends('layouts.app')

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
                            <!-- Основной заголовок -->
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $squad->name }}</h1>
                            
                            <!-- Теги и информация -->
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $shift->name }}
                                </span>
                                
                                @if($squad->age_group)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $squad->age_group }}
                                    </span>
                                @endif
                                
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $squad->getCurrentChildrenCount() }} {{ $squad->getCurrentChildrenCount() == 1 ? 'ребенок' : ($squad->getCurrentChildrenCount() <= 4 ? 'ребенка' : 'детей') }}
                                </span>
                                
                                <span class="text-sm text-gray-500">
                                    {{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Кнопки действий -->
                        <div class="flex items-center gap-2 lg:flex-shrink-0">
                            <a href="{{ route('admin.shifts.squads.edit', [$shift, $squad]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Редактировать
                            </a>
                            
                            <a href="{{ route('admin.shifts.squads.index', $shift) }}" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            
                            <button type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                            
                            <button type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Основная информация -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Информация о вожатой -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Вожатая отряда</h3>
                            <div class="flex items-center gap-4">
                                @if($squad->counselor)
                                    <div class="flex-shrink-0">
                                        @if($squad->counselor->avatar)
                                            <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($squad->counselor->avatar) }}" alt="Фото {{ $squad->counselor->full_name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-lg font-medium text-gray-900">
                                            {{ $squad->counselor->full_name ?: $squad->counselor->email }}
                                        </p>
                                        @if($squad->counselor->phone)
                                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $squad->counselor->phone }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('admin.staff.show', $squad->counselor) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-2" 
                                           title="Профиль вожатой">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7l-10 10"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-lg font-medium text-gray-500">Вожатая не назначена</p>
                                        <p class="text-sm text-gray-400">Назначьте вожатую в настройках отряда</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('admin.shifts.squads.edit', [$shift, $squad]) }}" 
                                           class="text-orange-600 hover:text-orange-800 p-2" 
                                           title="Назначить вожатую">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Список детей -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Дети в отряде 
                                    <span class="text-sm font-normal text-gray-500">({{ $squad->children->count() }})</span>
                                </h3>
                                <a href="{{ route('admin.children.index') }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Управление детьми
                                </a>
                            </div>
                            
                            <!-- Форма добавления ребенка -->
                            @if($availableChildren->count() > 0)
                                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-900 mb-3">Добавить ребенка в отряд</h4>
                                    <form method="POST" action="{{ route('admin.shifts.squads.add-child', [$shift, $squad]) }}" class="flex gap-3">
                                        @csrf
                                        <div class="flex-1">
                                            <select name="child_id" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('child_id') border-red-300 @enderror">
                                                <option value="">Выберите ребенка...</option>
                                                @foreach($availableChildren as $child)
                                                    <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                                        {{ $child->full_name }} ({{ $child->age_text }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" 
                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Добавить
                                        </button>
                                    </form>
                                    @error('child_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-600">
                                        Все дети уже добавлены в отряды или нет доступных детей.
                                        <a href="{{ route('admin.children.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            Создать нового ребенка
                                        </a>
                                    </p>
                                </div>
                            @endif
                            
                            @if($squad->children->count() > 0)
                                <div class="space-y-4">
                                    @foreach($squad->children as $child)
                                        <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow duration-200 overflow-hidden">
                                            <div class="flex items-start space-x-3">
                                                <!-- Аватар -->
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $child->avatar_url }}" 
                                                         alt="Фото {{ $child->full_name }}" 
                                                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-100">
                                                </div>
                                                
                                                <!-- Основная информация -->
                                                <div class="flex-1 min-w-0 overflow-hidden">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="text-sm font-semibold text-gray-900 truncate">
                                                                {{ $child->full_name }}
                                                            </h4>
                                                            <div class="mt-1 flex flex-wrap items-center gap-1">
                                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full whitespace-nowrap">
                                                                    {{ $child->age_text }}
                                                                </span>
                                                                <!-- Статус ребенка в отряде -->
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $child->getStatusClassInSquad($squad->id) }}">
                                                                    {{ $child->getStatusInSquad($squad->id) }}
                                                                </span>
                                                            </div>
                                                            
                                                            @if($child->hasEarlyDepartureFromSquad($squad->id))
                                                                @php $departure = $child->getEarlyDepartureFromSquad($squad->id); @endphp
                                                                <div class="mt-2 text-xs text-red-600 bg-red-50 px-2 py-1 rounded inline-block max-w-full">
                                                                    <svg class="inline w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <span class="truncate">Выехал {{ $departure['date_formatted'] }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Действия -->
                                                        <div class="flex items-center gap-1 flex-shrink-0 ml-2">
                                                            <a href="{{ route('admin.children.show', $child) }}" 
                                                               class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors flex-shrink-0" 
                                                               title="Просмотр профиля">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                            </a>
                                                            
                                                            <!-- Управление досрочным выездом -->
                                                            @if($child->hasEarlyDepartureFromSquad($squad->id))
                                                                <!-- Кнопка отмены досрочного выезда -->
                                                                <form method="POST" 
                                                                      action="{{ route('admin.shifts.squads.child-cancel-early-departure', [$shift, $squad, $child]) }}" 
                                                                      class="inline flex-shrink-0"
                                                                      onsubmit="return confirm('Вы уверены, что хотите отменить отметку досрочного выезда для {{ $child->full_name }}?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="p-1 text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition-colors" 
                                                                            title="Отменить досрочный выезд">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <!-- Кнопка отметки досрочного выезда -->
                                                                <a href="{{ route('admin.shifts.squads.child-early-departure', [$shift, $squad, $child]) }}" 
                                                                   class="p-1 text-orange-600 hover:text-orange-800 hover:bg-orange-50 rounded transition-colors flex-shrink-0" 
                                                                   title="Отметить досрочный выезд">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                            
                                                            <form method="POST" 
                                                                  action="{{ route('admin.shifts.squads.remove-child', [$shift, $squad, $child]) }}" 
                                                                  class="inline flex-shrink-0"
                                                                  onsubmit="return confirm('Вы уверены, что хотите исключить {{ $child->full_name }} из отряда?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors" 
                                                                        title="Исключить из отряда">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">В отряде пока нет детей</h3>
                                    <p class="mt-1 text-sm text-gray-500">Добавьте детей в отряд для начала работы</p>
                                    @if($availableChildren->count() > 0)
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 mb-2">Доступно {{ $availableChildren->count() }} детей для добавления</p>
                                        </div>
                                    @else
                                        <div class="mt-4">
                                            <a href="{{ route('admin.children.create') }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Создать первого ребенка
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if($squad->description)
                            <!-- Описание -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Описание</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $squad->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Боковая панель -->
                    <div class="space-y-6">
                        <!-- Информация о смене -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Смена</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Название</dt>
                                    <dd class="text-sm text-gray-900">{{ $shift->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Период</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Статус</dt>
                                    <dd class="text-sm">
                                        @if($shift->isActive())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Активна
                                            </span>
                                        @elseif($shift->start_date > now())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Предстоящая
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Завершена
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Действия -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Действия</h3>
                            <div class="space-y-3">
                                <a href="{{ route('admin.shifts.squads.edit', [$shift, $squad]) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Редактировать отряд
                                </a>
                                <form action="{{ route('admin.shifts.squads.destroy', [$shift, $squad]) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить отряд &quot;{{ $squad->name }}&quot;? Это действие нельзя отменить.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Удалить отряд
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 