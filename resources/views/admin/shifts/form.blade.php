@php
    $shift = $shift ?? null;
    $isEdit = isset($shift) && $shift->exists;
@endphp

<div class="space-y-4">
    <!-- Название смены -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Название смены <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $shift?->name) }}" required 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Дата начала -->
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Дата начала <span class="text-red-500">*</span></label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $shift?->start_date?->format('Y-m-d')) }}" required 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('start_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Дата окончания -->
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">Дата окончания <span class="text-red-500">*</span></label>
        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $shift?->end_date?->format('Y-m-d')) }}" required 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('end_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 