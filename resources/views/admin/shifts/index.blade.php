@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center gap-4 mb-6">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">Управление сменами</h1>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.shifts.create') }}" class="inline-flex items-center bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm font-medium">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Создать
                        </a>
                    </div>
                </div>

                <!-- Фильтры -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <form method="GET" action="{{ route('admin.shifts.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Фильтр по названию -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ request('name') }}"
                                       placeholder="Поиск по названию..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>

                            <!-- Фильтр по дате начала (от) -->
                            <div>
                                <label for="start_date_from" class="block text-sm font-medium text-gray-700 mb-1">Дата начала от</label>
                                <input type="date" 
                                       name="start_date_from" 
                                       id="start_date_from" 
                                       value="{{ request('start_date_from') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>

                            <!-- Фильтр по дате начала (до) -->
                            <div>
                                <label for="start_date_to" class="block text-sm font-medium text-gray-700 mb-1">Дата начала до</label>
                                <input type="date" 
                                       name="start_date_to" 
                                       id="start_date_to" 
                                       value="{{ request('start_date_to') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>

                            <!-- Фильтр по статусу -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                                <select name="status" 
                                        id="status" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Все статусы</option>
                                    <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Предстоящие</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Активные</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Завершенные</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Применить
                            </button>
                            
                            @if(request()->hasAny(['name', 'start_date_from', 'start_date_to', 'status']))
                                <a href="{{ route('admin.shifts.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Сбросить
                                </a>
                            @endif
                        </div>
                    </form>
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

                @if($shifts->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Нет смен</h3>
                        <p class="mt-1 text-sm text-gray-500">Создайте первую смену для начала работы.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.shifts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Создать смену
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Название
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Даты
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Статус
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Сотрудники
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Отряды
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Дети
                                    </th>
                                    <th class="relative px-6 py-3">
                                        <span class="sr-only">Действия</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($shifts as $shift)
                                    <tr class="hover:bg-gray-50 cursor-pointer" ondblclick="window.location.href='{{ route('admin.shifts.edit', $shift) }}'" title="Двойной клик для редактирования">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $shift->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($shift->staff_count > 0)
                                                <a href="{{ route('admin.staff.index') }}?shift_id={{ $shift->id }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                                    {{ $shift->staff_count }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($shift->squads_count > 0)
                                                <a href="{{ route('admin.shifts.squads.index', $shift) }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                                    {{ $shift->squads_count }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($shift->children_count > 0)
                                                <a href="{{ route('admin.children.index') }}?shift_id={{ $shift->id }}" 
                                                   class="text-purple-600 hover:text-purple-800 font-medium">
                                                    {{ $shift->children_count }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.shifts.show', $shift) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Просмотр">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.shifts.squads.index', $shift) }}" class="text-green-600 hover:text-green-900 p-1" title="Отряды">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.shifts.edit', $shift) }}" class="text-yellow-600 hover:text-yellow-900 p-1" title="Редактировать">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                @if(!$shift->staff()->exists())
                                                    <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1" onclick="return confirm('Вы уверены?')" title="Удалить">
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

                    <div class="mt-4">
                        {{ $shifts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 