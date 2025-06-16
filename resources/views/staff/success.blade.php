@extends('layouts.mobile')

@section('title', 'Регистрация завершена')

@section('header')
<div class="flex items-center justify-center">
    <h1 class="text-lg font-semibold">Регистрация завершена</h1>
</div>
@endsection

@section('content')
<div class="px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="mb-4">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Поздравляем!</h2>
        <p class="text-gray-600 mb-6">Ваша регистрация успешно завершена.</p>
        
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4">
                <p class="text-sm text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        @endif
        
        <div class="space-y-3">
            <a href="{{ route('staff.dashboard') }}" class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Перейти в личный кабинет
            </a>
            
            <a href="{{ route('unified.login') }}" class="block w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Войти в систему
            </a>
        </div>
    </div>
</div>
@endsection 