@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Создать нового сотрудника</h1>
                    <a href="{{ route('admin.staff.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                После создания сотрудника будет сгенерирована уникальная ссылка для регистрации.
                                Отправьте эту ссылку сотруднику для завершения регистрации.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.staff.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Email (обязательное поле) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="employee@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Смены (опционально) -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-medium text-gray-700">Смены</label>
                                <div class="space-x-2">
                                    <button type="button" onclick="selectAllShifts()" class="text-xs text-blue-600 hover:text-blue-800">Выбрать все</button>
                                    <button type="button" onclick="deselectAllShifts()" class="text-xs text-gray-600 hover:text-gray-800">Снять все</button>
                                </div>
                            </div>
                            <div class="space-y-3 bg-gray-50 p-4 rounded-lg max-h-64 overflow-y-auto">
                                @php $selectedShifts = old('shifts', []) @endphp
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
                            @error('shifts')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Фамилия (опционально) -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Фамилия</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Имя (опционально) -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Имя</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.staff.index') }}" class="text-gray-600 hover:text-gray-900">
                            Отмена
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Создать и получить ссылку
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