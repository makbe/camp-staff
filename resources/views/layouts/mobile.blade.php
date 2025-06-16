<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Учет сотрудников лагеря')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Mobile App Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    
    <style>
        /* Мобильные оптимизации */
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
        
        html, body {
            overflow-x: hidden;
            background-color: #f3f4f6;
        }
        
        /* Стиль для iOS нотча */
        @supports (padding-top: env(safe-area-inset-top)) {
            .safe-top {
                padding-top: env(safe-area-inset-top);
            }
            .safe-bottom {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
        
        /* Кнопки для мобильных устройств */
        .mobile-button {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Поля ввода для мобильных */
        input, select, textarea {
            font-size: 16px !important; /* Предотвращает зум на iOS */
        }
        
        /* Скроллбар */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Верхняя панель навигации -->
        @hasSection('header')
            <header class="bg-white shadow-sm border-b border-gray-200 safe-top">
                <div class="px-4 py-3">
                    @yield('header')
                </div>
            </header>
        @endif
        
        <!-- Основной контент -->
        <main class="pb-20">
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 m-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 m-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
        
        <!-- Нижняя навигация для мобильных -->
        @hasSection('bottom-nav')
            <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 safe-bottom">
                <div class="flex justify-around py-2">
                    @yield('bottom-nav')
                </div>
            </nav>
        @endif
    </div>
    
    @stack('scripts')
</body>
</html> 