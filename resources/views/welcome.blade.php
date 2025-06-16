<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Система управления персоналом лагеря</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Система управления персоналом
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Добро пожаловать!
                    </p>
                </div>
                
                @if(Auth::check() || Auth::guard('staff')->check())
                    <div class="mt-8 space-y-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-4">
                                Вы уже авторизованы как 
                                <span class="font-medium">
                                    @if(Auth::check())
                                        Администратор
                    @else
                                        Сотрудник
            @endif
                                </span>
                            </p>
                        </div>
                        
                        <div class="rounded-md shadow">
                            @if(Auth::check())
                                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Перейти в админ-панель
                                </a>
                            @else
                                <a href="{{ route('staff.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                    Перейти в личный кабинет
                                </a>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <form method="POST" action="{{ Auth::check() ? route('logout') : route('staff.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                    Выйти из системы
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="mt-8 space-y-4">
                                            <div class="rounded-md shadow">
                        <a href="{{ route('unified.login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            Вход в систему
                        </a>
                    </div>
                        
                        <div class="rounded-md shadow">
                            <a href="{{ route('staff.join') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                Регистрация сотрудника
                            </a>
                        </div>
                </div>
                @endif
                </div>
        </div>
    </body>
</html>
