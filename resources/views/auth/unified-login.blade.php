<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Вход в систему</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Вход в систему
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Выберите тип учетной записи
                </p>
            </div>

            <!-- Переключатель типа входа -->
            <div class="flex rounded-lg bg-gray-100 p-1">
                <button type="button" id="staff-tab" onclick="switchToStaff()" 
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 bg-white text-gray-900 shadow">
                    Сотрудник
                </button>
                <button type="button" id="admin-tab" onclick="switchToAdmin()" 
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 text-gray-500 hover:text-gray-700">
                    Администратор
                </button>
            </div>

            <!-- Форма входа для сотрудников -->
            <div id="staff-form" class="mt-8">
                <form class="space-y-6" action="{{ route('staff.login.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_type" value="staff">

                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="staff-email" class="block text-sm font-medium text-gray-700">Email адрес</label>
                            <input id="staff-email" name="email" type="email" autocomplete="email" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                                   placeholder="ваш@email.com" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label for="staff-password" class="block text-sm font-medium text-gray-700">Пароль</label>
                            <input id="staff-password" name="password" type="password" autocomplete="current-password" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Войти как сотрудник
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Еще не зарегистрированы? 
                            <a href="{{ route('staff.join') }}" class="font-medium text-green-600 hover:text-green-500">
                                Зарегистрироваться
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Форма входа для администраторов -->
            <div id="admin-form" class="mt-8 hidden">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_type" value="admin">

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="admin-email" class="block text-sm font-medium text-gray-700">Email администратора</label>
                            <input id="admin-email" name="email" type="email" autocomplete="email" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                   placeholder="admin@camp.com" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label for="admin-password" class="block text-sm font-medium text-gray-700">Пароль</label>
                            <input id="admin-password" name="password" type="password" autocomplete="current-password" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-900">
                                Запомнить меня
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Забыли пароль?
                                </a>
                            </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Войти как администратор
                        </button>
                    </div>

                    @if(app()->environment('local'))
                        <div class="mt-4 p-4 bg-gray-100 rounded-md">
                            <p class="text-sm text-gray-600 text-center">
                                Тестовые данные:<br>
                                Email: admin@camp.com<br>
                                Пароль: password
                            </p>
                        </div>
                    @endif
                </form>
            </div>

            <div class="text-center">
                <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Вернуться на главную
                </a>
            </div>
        </div>
    </div>

    <script>
        function switchToStaff() {
            document.getElementById('staff-form').classList.remove('hidden');
            document.getElementById('admin-form').classList.add('hidden');
            
            document.getElementById('staff-tab').classList.add('bg-white', 'text-gray-900', 'shadow');
            document.getElementById('staff-tab').classList.remove('text-gray-500');
            
            document.getElementById('admin-tab').classList.remove('bg-white', 'text-gray-900', 'shadow');
            document.getElementById('admin-tab').classList.add('text-gray-500');
        }

        function switchToAdmin() {
            document.getElementById('admin-form').classList.remove('hidden');
            document.getElementById('staff-form').classList.add('hidden');
            
            document.getElementById('admin-tab').classList.add('bg-white', 'text-gray-900', 'shadow');
            document.getElementById('admin-tab').classList.remove('text-gray-500');
            
            document.getElementById('staff-tab').classList.remove('bg-white', 'text-gray-900', 'shadow');
            document.getElementById('staff-tab').classList.add('text-gray-500');
        }

        // Проверка URL для автоматического переключения
        if (window.location.pathname === '/login') {
            switchToAdmin();
        }
    </script>
</body>
</html> 