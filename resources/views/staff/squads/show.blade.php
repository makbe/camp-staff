@extends('layouts.mobile')

@section('title', $squad->name)

@section('header')
<div class="flex items-center justify-between">
    <div class="flex items-center space-x-3">
        <a href="{{ route('staff.squads.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1 min-w-0">
            <h1 class="text-lg font-semibold truncate">{{ $squad->name }}</h1>
            <p class="text-sm text-gray-500">{{ $squad->shift->name }}</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="p-4 space-y-4">
    <!-- Информация об отряде -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-gray-900">{{ $squad->name }}</h2>
                <p class="text-sm text-gray-500">{{ $squad->shift->name }}</p>
            </div>
        </div>
        
        @if($squad->description)
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Описание</h3>
                <p class="text-sm text-gray-600">{{ $squad->description }}</p>
            </div>
        @endif
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            @if($squad->age_group)
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">Возрастная группа</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $squad->age_group }}
                    </span>
                </div>
            @endif
            
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-1">Количество детей</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $squad->children->count() }} детей
                </span>
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-200">
            <h3 class="text-sm font-medium text-gray-900 mb-2">Период смены</h3>
            <p class="text-sm text-gray-600">
                {{ $squad->shift->start_date->format('d.m.Y') }} — {{ $squad->shift->end_date->format('d.m.Y') }}
            </p>
        </div>
    </div>
    
    <!-- Список детей -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Дети отряда</h3>
            <span class="text-sm text-gray-500">{{ $squad->children->count() }}</span>
        </div>
        
        @if($squad->children->count() > 0)
            <div class="space-y-3">
                @foreach($squad->children as $child)
                    <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                        <div class="flex-shrink-0">
                            @if($child->avatar)
                                <img src="{{ Storage::url($child->avatar) }}" alt="{{ $child->full_name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $child->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $child->age_text }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $child->birth_date_formatted }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-medium text-gray-900 mb-1">Нет детей в отряде</h4>
                <p class="text-xs text-gray-500">Дети будут добавлены администратором</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('bottom-nav')
    <a href="{{ route('staff.dashboard') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-blue-600">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
        </svg>
        <span class="text-xs">Главная</span>
    </a>
    
    <a href="{{ route('staff.squads.index') }}" class="flex flex-col items-center py-2 px-3 text-blue-600">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
        </svg>
        <span class="text-xs">Отряды</span>
    </a>
@endsection

@php
use Illuminate\Support\Facades\Storage;
@endphp 