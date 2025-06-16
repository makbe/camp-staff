@extends('layouts.mobile')

@section('title', 'Личный кабинет')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="text-lg font-semibold">Личный кабинет</h1>
    <form action="{{ route('staff.logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
            Выйти
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="px-4 py-6 space-y-6">
    <!-- Информация о сотруднике -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center space-x-4">
            @if($staff->avatar)
                <img src="{{ Storage::url($staff->avatar) }}" alt="Аватар" class="w-20 h-20 rounded-full object-cover">
            @else
                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            @endif
            
            <div>
                <h2 class="text-xl font-semibold">{{ $staff->full_name }}</h2>
                <p class="text-sm text-gray-600">{{ ucfirst($staff->position) }}</p>
                @if($staff->shift)
                    <p class="text-sm text-gray-500">{{ $staff->shift->name }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Быстрые действия для вожатых -->
    @if($staff->position === 'вожатый')
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="font-medium text-gray-900 mb-3">Мои отряды</h3>
            
            <a href="{{ route('staff.squads.index') }}" class="block w-full bg-blue-600 text-white text-center py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                <div class="flex items-center justify-center space-x-2">
                    <span class="font-medium">Перейти к отрядам</span>
                </div>
            </a>
            
            @php
                $squadsCount = $staff->squads()->count();
            @endphp
            
            @if($squadsCount > 0)
                <p class="text-sm text-gray-600 mt-2 text-center">
                    У вас {{ $squadsCount }} {{ $squadsCount == 1 ? 'отряд' : ($squadsCount < 5 ? 'отряда' : 'отрядов') }}
                </p>
            @else
                <p class="text-sm text-gray-500 mt-2 text-center">
                    Вы пока не назначены ни на один отряд
                </p>
            @endif
        </div>
    @endif
    
    <!-- Основная информация -->
    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
        <h3 class="font-medium text-gray-900 mb-3">Контактная информация</h3>
        
        <div class="space-y-2">
            <div class="flex">
                <span class="text-sm text-gray-600">Email:</span>
                <span class="text-sm font-medium ml-2">{{ $staff->email }}</span>
            </div>
            
            <div class="flex">
                <span class="text-sm text-gray-600">Телефон:</span>
                <span class="text-sm font-medium ml-2">{{ $staff->phone }}</span>
            </div>
            
            <div class="flex">
                <span class="text-sm text-gray-600">Telegram:</span>
                <span class="text-sm font-medium ml-2">{{ $staff->telegram }}</span>
            </div>
        </div>
    </div>
    
    <!-- Статус самозанятого -->
    @if($staff->is_self_employed)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">Вы зарегистрированы как самозанятый</p>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Документы -->
    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
        <h3 class="font-medium text-gray-900 mb-3">Документы</h3>
        
        <div class="space-y-2">
            @if($staff->passport_series && $staff->passport_number)
                <div>
                    <span class="text-sm text-gray-600">Паспорт:</span>
                    <span class="text-sm font-medium ml-2">{{ $staff->passport_series }} {{ $staff->passport_number }}</span>
                </div>
            @endif
            
            @if($staff->inn)
                <div>
                    <span class="text-sm text-gray-600">ИНН:</span>
                    <span class="text-sm font-medium ml-2">{{ $staff->inn }}</span>
                </div>
            @endif
            
            @if($staff->snils)
                <div>
                    <span class="text-sm text-gray-600">СНИЛС:</span>
                    <span class="text-sm font-medium ml-2">{{ $staff->snils }}</span>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Банковские реквизиты -->
    @if($staff->bank_name || $staff->bank_account)
        <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
            <h3 class="font-medium text-gray-900 mb-3">Банковские реквизиты</h3>
            
            <div class="space-y-2">
                @if($staff->bank_name)
                    <div>
                        <span class="text-sm text-gray-600">Банк:</span>
                        <span class="text-sm font-medium ml-2">{{ $staff->bank_name }}</span>
                    </div>
                @endif
                
                @if($staff->bank_account)
                    <div>
                        <span class="text-sm text-gray-600">Счет:</span>
                        <span class="text-sm font-medium ml-2">{{ $staff->bank_account }}</span>
                    </div>
                @endif
                
                @if($staff->bank_bik)
                    <div>
                        <span class="text-sm text-gray-600">БИК:</span>
                        <span class="text-sm font-medium ml-2">{{ $staff->bank_bik }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <!-- Рейтинг (если есть) -->
    @if($staff->rating)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="font-medium text-gray-900 mb-3">Рейтинг</h3>
            <div class="flex items-center">
                @for($i = 1; $i <= 10; $i++)
                    <svg class="w-5 h-5 {{ $i <= $staff->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="ml-2 text-sm text-gray-600">{{ $staff->rating }}/10</span>
            </div>
        </div>
    @endif
</div>
@endsection

@if($staff->position === 'вожатый')
    @section('bottom-nav')
        <a href="{{ route('staff.dashboard') }}" class="flex flex-col items-center py-2 px-3 text-blue-600">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
            </svg>
            <span class="text-xs">Главная</span>
        </a>
        
        <a href="{{ route('staff.squads.index') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-600">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-xs">Отряды</span>
        </a>
    @endsection
@endif

@php
use Illuminate\Support\Facades\Storage;
@endphp 