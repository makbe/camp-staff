@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Редактировать сотрудника</h1>
                    <a href="{{ route('admin.staff.show', $staff) }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Обнаружены ошибки при сохранении
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

                <form action="{{ route('admin.staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Основная информация -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Основная информация</h3>
                            
                            <!-- Аватар -->
                            <div class="mb-6">
                                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Фотография</label>
                                <div class="flex items-center space-x-4">
                                    @if($staff->avatar)
                                        <img src="{{ Storage::url($staff->avatar) }}" alt="Аватар" class="h-20 w-20 rounded-full object-cover">
                                    @else
                                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-blue-50 file:text-blue-700
                                            hover:file:bg-blue-100">
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG. Максимум 2MB.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Фамилия <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $staff->last_name) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">Имя <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $staff->first_name) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="middle_name" class="block text-sm font-medium text-gray-700">Отчество</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $staff->middle_name) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $staff->phone) }}"
                                           placeholder="+7ХХХХХХХХХХ"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="telegram" class="block text-sm font-medium text-gray-700">Telegram</label>
                                    <input type="text" name="telegram" id="telegram" value="{{ old('telegram', $staff->telegram) }}"
                                           placeholder="@username"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700">Должность</label>
                                    <select name="position" id="position" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Не указана</option>
                                        @foreach(\App\Models\Staff::getPositions() as $position)
                                            <option value="{{ $position }}" {{ old('position', $staff->position) == $position ? 'selected' : '' }}>
                                                {{ ucfirst($position) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Смены</label>
                                        <div class="space-x-2">
                                            <button type="button" onclick="selectAllShifts()" class="text-xs text-blue-600 hover:text-blue-800">Выбрать все</button>
                                            <button type="button" onclick="deselectAllShifts()" class="text-xs text-gray-600 hover:text-gray-800">Снять все</button>
                                        </div>
                                    </div>
                                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg max-h-64 overflow-y-auto">
                                        @php $selectedShifts = old('shifts', $staff->shifts->pluck('id')->toArray()) @endphp
                                        @foreach($shifts as $shift)
                                            <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-100 p-2 rounded">
                                                <input type="checkbox" 
                                                       name="shifts[]" 
                                                       value="{{ $shift->id }}" 
                                                       {{ in_array($shift->id, $selectedShifts) ? 'checked' : '' }}
                                                       class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900">{{ $shift->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $shift->start_date->format('d.m.Y') }} - {{ $shift->end_date->format('d.m.Y') }}
                                                        @if($shift->staff_count > 0)
                                                            • {{ $shift->staff_count }} сотрудников
                                                        @endif
                                                    </div>
                                                    @if($shift->isActive())
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                            Активна
                                                        </span>
                                                    @elseif($shift->start_date > now())
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                            Предстоящая
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                                            Завершена
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                        @if($shifts->isEmpty())
                                            <div class="text-center py-4 text-gray-500">
                                                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Нет доступных смен
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Выберите смены, в которых будет участвовать сотрудник</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="is_self_employed" class="flex items-center">
                                        <input type="checkbox" name="is_self_employed" id="is_self_employed" value="1"
                                               {{ old('is_self_employed', $staff->is_self_employed) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Самозанятый</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Административная информация -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Административная информация</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700">Рейтинг (1-10)</label>
                                    <input type="number" name="rating" id="rating" min="1" max="10" value="{{ old('rating', $staff->rating) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="admin_comment" class="block text-sm font-medium text-gray-700">Комментарий администратора</label>
                                    <textarea name="admin_comment" id="admin_comment" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('admin_comment', $staff->admin_comment) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Документы (опционально) -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Документы (опционально)</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="passport_series" class="block text-sm font-medium text-gray-700">Серия паспорта</label>
                                    <input type="text" name="passport_series" id="passport_series" maxlength="4" value="{{ old('passport_series', $staff->passport_series) }}"
                                           placeholder="0000"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="passport_number" class="block text-sm font-medium text-gray-700">Номер паспорта</label>
                                    <input type="text" name="passport_number" id="passport_number" maxlength="6" value="{{ old('passport_number', $staff->passport_number) }}"
                                           placeholder="000000"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="passport_issued_date" class="block text-sm font-medium text-gray-700">Дата выдачи</label>
                                    <input type="date" name="passport_issued_date" id="passport_issued_date" value="{{ old('passport_issued_date', $staff->passport_issued_date?->format('Y-m-d')) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="passport_code" class="block text-sm font-medium text-gray-700">Код подразделения</label>
                                    <input type="text" name="passport_code" id="passport_code" maxlength="7" value="{{ old('passport_code', $staff->passport_code) }}"
                                           placeholder="000-000"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="passport_issued_by" class="block text-sm font-medium text-gray-700">Кем выдан</label>
                                    <input type="text" name="passport_issued_by" id="passport_issued_by" value="{{ old('passport_issued_by', $staff->passport_issued_by) }}"
                                           placeholder="Отделение УФМС России по..."
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="inn" class="block text-sm font-medium text-gray-700">ИНН</label>
                                    <input type="text" name="inn" id="inn" maxlength="12" value="{{ old('inn', $staff->inn) }}"
                                           placeholder="000000000000"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="snils" class="block text-sm font-medium text-gray-700">СНИЛС</label>
                                    <input type="text" name="snils" id="snils" maxlength="11" value="{{ old('snils', $staff->snils) }}"
                                           placeholder="00000000000"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Банковские реквизиты (опционально) -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Банковские реквизиты (опционально)</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Название банка</label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $staff->bank_name) }}"
                                           placeholder="Сбербанк"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="bank_account" class="block text-sm font-medium text-gray-700">Номер счета</label>
                                    <input type="text" name="bank_account" id="bank_account" maxlength="20" value="{{ old('bank_account', $staff->bank_account) }}"
                                           placeholder="40817810000000000000"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="bank_bik" class="block text-sm font-medium text-gray-700">БИК</label>
                                    <input type="text" name="bank_bik" id="bank_bik" maxlength="9" value="{{ old('bank_bik', $staff->bank_bik) }}"
                                           placeholder="044525225"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="bank_correspondent_account" class="block text-sm font-medium text-gray-700">Корр. счет</label>
                                    <input type="text" name="bank_correspondent_account" id="bank_correspondent_account" maxlength="20" value="{{ old('bank_correspondent_account', $staff->bank_correspondent_account) }}"
                                           placeholder="30101810400000000225"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.staff.show', $staff) }}" class="text-gray-600 hover:text-gray-900">
                            Отмена
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectAllShifts() {
    const checkboxes = document.querySelectorAll('input[name="shifts[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllShifts() {
    const checkboxes = document.querySelectorAll('input[name="shifts[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endsection 