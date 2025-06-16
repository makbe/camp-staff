@extends('layouts.mobile')

@section('title', 'Ссылка недействительна')

@section('header')
<div class="flex items-center justify-center">
    <h1 class="text-lg font-semibold">Ссылка недействительна</h1>
</div>
@endsection

@section('content')
<div class="px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="mb-4">
            <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Ссылка недействительна</h2>
        
        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-4">
                <p class="text-sm text-red-800">
                    {{ session('error') }}
                </p>
            </div>
        @else
            <p class="text-gray-600 mb-6">Эта ссылка для регистрации больше не действительна. Возможно, она уже была использована или истек срок её действия.</p>
        @endif
        
        <div class="space-y-3">
            <p class="text-sm text-gray-500 mb-4">
                Если вы считаете, что это ошибка, обратитесь к администратору.
            </p>
            
            <a href="{{ route('unified.login') }}" class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Войти в систему
            </a>
            
            <a href="{{ route('staff.join') }}" class="block w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Самостоятельная регистрация
            </a>
        </div>
    </div>
</div>
@endsection 