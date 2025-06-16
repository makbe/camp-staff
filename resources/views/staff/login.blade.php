@extends('layouts.mobile')

@section('title', 'Вход в личный кабинет')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Вход в личный кабинет</h1>
            
            <form action="{{ route('staff.login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Пароль -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Кнопка входа -->
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mobile-button">
                    Войти
                </button>
            </form>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Еще не зарегистрированы? 
                <a href="{{ route('staff.join') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Зарегистрироваться
                </a>
            </p>
        </div>
        
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Администратор? 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Войти в админку
                </a>
            </p>
        </div>
    </div>
</div>
@endsection 