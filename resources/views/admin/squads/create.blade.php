@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Создать отряд</h1>
                    <a href="{{ route('admin.shifts.squads.index', $shift) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Назад к отрядам
                    </a>
                </div>

                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <h2 class="text-lg font-semibold text-blue-900">Смена: {{ $shift->name }}</h2>
                    <p class="text-blue-700">{{ $shift->description }}</p>
                    <p class="text-sm text-blue-600">
                        {{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }}
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.shifts.squads.store', $shift) }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Название отряда *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                        <textarea name="description" id="description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Краткое описание отряда">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="age_group" class="block text-sm font-medium text-gray-700">Возрастная группа</label>
                        <select name="age_group" id="age_group" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Выберите возрастную группу</option>
                            @foreach($ageGroups as $ageGroup)
                                <option value="{{ $ageGroup }}" {{ old('age_group') == $ageGroup ? 'selected' : '' }}>
                                    {{ $ageGroup }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="counselor_id" class="block text-sm font-medium text-gray-700">Вожатый</label>
                        <select name="counselor_id" 
                                id="counselor_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Выберите вожатого</option>
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->id }}" {{ old('counselor_id') == $counselor->id ? 'selected' : '' }}>
                                    {{ $counselor->full_name ?: $counselor->name ?: $counselor->email }}
                                </option>
                            @endforeach
                        </select>
                        @if($counselors->isEmpty())
                            <p class="mt-1 text-sm text-gray-500">
                                Нет доступных вожатых для этой смены
                            </p>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.shifts.squads.index', $shift) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Создать отряд
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 