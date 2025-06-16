@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Создать новую смену</h1>
                    <a href="{{ route('admin.shifts.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <form action="{{ route('admin.shifts.store') }}" method="POST">
                    @csrf
                    
                    @include('admin.shifts.form')
                    
                    <div class="mt-6 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.shifts.index') }}" class="text-gray-600 hover:text-gray-900">
                            Отмена
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Создать смену
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 