@extends('layouts.app')

@section('title', 'Отметить досрочный выезд - ' . $child->full_name . ' из отряда ' . $squad->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900">
                        Отметить досрочный выезд из отряда
                    </h1>
                    <a href="{{ route('admin.shifts.squads.show', [$squad->shift, $squad]) }}" 
                       class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Информация о ребенке и отряде -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($child->full_name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $child->full_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $child->age_text }}, родился {{ $child->birth_date_formatted }}</p>
                            </div>
                        </div>
                        <div class="border-t border-blue-200 pt-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700">Отряд:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $squad->name }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm font-medium text-gray-700">Смена:</span>
                                <span class="text-sm text-gray-600">{{ $squad->shift->name }}</span>
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm font-medium text-gray-700">Даты смены:</span>
                                <span class="text-sm text-gray-600">
                                    {{ $squad->shift->start_date->format('d.m.Y') }} - {{ $squad->shift->end_date->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Исправьте следующие ошибки:
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

                <form action="{{ route('admin.shifts.squads.child-mark-early-departure', [$squad->shift, $squad, $child]) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="early_departure_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Дата выезда из отряда <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="early_departure_date" 
                               name="early_departure_date" 
                               value="{{ old('early_departure_date') }}"
                               max="{{ date('Y-m-d') }}"
                               min="{{ date('Y-m-d', strtotime('-30 days')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <p class="mt-1 text-sm text-gray-500">
                            Дата не может быть более 30 дней назад или в будущем
                        </p>
                    </div>

                    <div>
                        <label for="early_departure_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Причина досрочного выезда из отряда <span class="text-red-500">*</span>
                        </label>
                        <textarea id="early_departure_reason" 
                                  name="early_departure_reason" 
                                  rows="4" 
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Укажите причину досрочного выезда из отряда..."
                                  required>{{ old('early_departure_reason') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Максимум 1000 символов
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.shifts.squads.show', [$squad->shift, $squad]) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Отмена
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Отметить досрочный выезд
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 