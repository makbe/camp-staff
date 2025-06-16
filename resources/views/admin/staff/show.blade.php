@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Информация о сотруднике</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.staff.edit', $staff) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 inline-flex items-center" title="Редактировать">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Редактировать
                        </a>
                        <a href="{{ route('admin.staff.index') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2">
                            Назад к списку
                        </a>
                    </div>
                </div>

                <!-- Статус регистрации -->
                @if(!$staff->isRegistered())
                    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Сотрудник еще не завершил регистрацию
                                </p>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700 mb-2">
                                        Ссылка для регистрации: 
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ route('staff.register', ['token' => $staff->registration_token]) }}</code>
                                    </p>
                                    <form action="{{ route('admin.staff.regenerate-link', $staff) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                            Сгенерировать новую ссылку
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Регистрация завершена {{ $staff->registered_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Основная информация -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Основная информация</h3>
                        
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ФИО</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->full_name ?: 'Не указано' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->email }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Телефон</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->phone ?: 'Не указан' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Telegram</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->telegram ?: 'Не указан' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Должность</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->position ? ucfirst($staff->position) : 'Не указана' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Смены</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($staff->shifts->count() > 0)
                                        @foreach($staff->shifts as $shift)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1 mr-1">
                                                {{ $shift->name }} ({{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }})
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400">Не назначена</span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Самозанятый</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->is_self_employed ? 'Да' : 'Нет' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Документы -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Документы</h3>
                        
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Паспорт</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($staff->passport_series && $staff->passport_number)
                                        {{ $staff->passport_series }} {{ $staff->passport_number }}
                                    @else
                                        Не указан
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ИНН</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->inn ?: 'Не указан' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">СНИЛС</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->snils ?: 'Не указан' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Банковские реквизиты -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Банковские реквизиты</h3>
                        
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Банк</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->bank_name ?: 'Не указан' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Счет</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->bank_account ?: 'Не указан' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">БИК</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->bank_bik ?: 'Не указан' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Для администратора -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Административная информация</h3>
                        
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Рейтинг</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($staff->rating)
                                        {{ $staff->rating }}/10
                                    @else
                                        Не установлен
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Комментарий администратора</dt>
                                <dd class="text-sm text-gray-900">{{ $staff->admin_comment ?: 'Нет комментариев' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Действия -->
                <div class="mt-6 border-t pt-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Действия</h3>
                        <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого сотрудника? Это действие нельзя отменить.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Удалить сотрудника">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Удалить сотрудника
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 