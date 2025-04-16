@extends('layouts.filament-error')

@section('title', __('Acceso no autorizado'))

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 text-center px-6 py-12">
        <div class="flex flex-col items-center space-y-4">

			<svg class="w-20 h-20 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
			  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
			</svg>

			<h1 class="text-4xl font-bold text-gray-900">403</h1>

			<p class="text-lg text-gray-700">
			    {{ __('No tienes permisos para ver esta página.') }}
			</p>


        </div>
    </div>
@endsection
