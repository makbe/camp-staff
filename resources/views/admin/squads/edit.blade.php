@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Редактировать отряд "{{ $squad->name }}"</h1>
                        <p class="text-gray-600">Смена: {{ $shift->name }} ({{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }})</p>
                    </div>
                    <a href="{{ route('admin.shifts.squads.show', [$shift, $squad]) }}" class="text-gray-600 hover:text-gray-900">
                        Назад к отряду
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Ошибки валидации:
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.shifts.squads.update', [$shift, $squad]) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Название отряда -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Название отряда <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $squad->name) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Например: Орлята">
                        </div>

                        <!-- Описание -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea name="description" id="description" rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Краткое описание отряда">{{ old('description', $squad->description) }}</textarea>
                        </div>

                        <!-- Возрастная группа -->
                        <div>
                            <label for="age_group" class="block text-sm font-medium text-gray-700">Возрастная группа</label>
                            <select name="age_group" id="age_group" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Выберите возрастную группу</option>
                                @foreach($ageGroups as $ageGroup)
                                    <option value="{{ $ageGroup }}" {{ old('age_group', $squad->age_group) == $ageGroup ? 'selected' : '' }}>
                                        {{ $ageGroup }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Текущее количество детей (только для информации) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Текущее количество детей</label>
                            <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                                {{ $squad->getCurrentChildrenCount() }} детей
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Количество вычисляется автоматически на основе добавленных детей</p>
                        </div>

                        <!-- Вожатая -->
                        <div class="md:col-span-2">
                            <label for="counselor_id" class="block text-sm font-medium text-gray-700">Вожатая</label>
                            <select name="counselor_id" id="counselor_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Не назначена</option>
                                @foreach($counselors as $counselor)
                                    <option value="{{ $counselor->id }}" {{ old('counselor_id', $squad->counselor_id) == $counselor->id ? 'selected' : '' }}>
                                        {{ $counselor->full_name ?: $counselor->name ?: $counselor->email }}
                                        @if($counselor->phone) - {{ $counselor->phone }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @if($counselors->isEmpty())
                                <p class="mt-1 text-sm text-yellow-600">
                                    В этой смене нет свободных вожатых. Убедитесь, что сотрудники с должностью "вожатый" добавлены в смену.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.shifts.squads.show', [$shift, $squad]) }}" 
                           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Сохранить изменения
                        </button>
                    </div>
                </form>

                <!-- Форма удаления отряда (отдельно) -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form action="{{ route('admin.shifts.squads.destroy', [$shift, $squad]) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить отряд &quot;{{ $squad->name }}&quot;? Это действие нельзя отменить.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Удалить отряд
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 