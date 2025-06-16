@extends('layouts.mobile')

@section('title', 'Мои отряды')

@section('header')
<div class="flex items-center justify-between">
    <div class="flex items-center space-x-3">
        <a href="{{ route('staff.dashboard') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-lg font-semibold">Мои отряды</h1>
    </div>
    <div class="text-sm text-gray-500">{{ $squads->count() }}</div>
</div>
@endsection

@section('content')
<div class="p-4 space-y-4">
    @if($squads->count() > 0)
        @foreach($squads as $squad)
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <a href="{{ route('staff.squads.show', $squad) }}" class="block">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $squad->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $squad->shift->name }}</p>
                                </div>
                            </div>
                            
                            @if($squad->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $squad->description }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    @if($squad->age_group)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $squad->age_group }}
                                        </span>
                                    @endif
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $squad->children->count() }} детей
                                    </span>
                                </div>
                                
                                <div class="flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @else
        <div class="text-center py-12">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Нет отрядов</h3>
            <p class="text-gray-500 text-sm">Вы пока не назначены вожатой ни одного отряда</p>
        </div>
    @endif
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="text-xs">Отряды</span>
    </a>
@endsection 