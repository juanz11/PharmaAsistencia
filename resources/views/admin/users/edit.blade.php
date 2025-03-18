@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Editar Usuario</h2>
        <a href="{{ route('admin.users.index') }}" class="bg-[#1a4175] text-gray-100 px-4 py-2 rounded hover:bg-[#15345d]">
            Volver
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nombre
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $user->name) }}" 
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}" 
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="identification">
                    Identificación
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="identification" 
                    type="text" 
                    name="identification" 
                    value="{{ old('identification', $user->identification) }}" 
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="location">
                    Ubicación
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="location" 
                    type="text" 
                    name="location" 
                    value="{{ old('location', $user->location) }}" 
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="department">
                    Departamento
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="department" 
                    name="department">
                    <option value="">Seleccionar departamento</option>
                    <option value="RECURSOS HUMANOS" {{ old('department', $user->department) == 'RECURSOS HUMANOS' ? 'selected' : '' }}>RECURSOS HUMANOS</option>
                    <option value="PRESIDENCIA" {{ old('department', $user->department) == 'PRESIDENCIA' ? 'selected' : '' }}>PRESIDENCIA</option>
                    <option value="ADMINISTRACION" {{ old('department', $user->department) == 'ADMINISTRACION' ? 'selected' : '' }}>ADMINISTRACION</option>
                    <option value="COMERCIAL" {{ old('department', $user->department) == 'COMERCIAL' ? 'selected' : '' }}>COMERCIAL</option>
                    <option value="MERCADEO" {{ old('department', $user->department) == 'MERCADEO' ? 'selected' : '' }}>MERCADEO</option>
                    <option value="CONSULTORIA JURIDICA" {{ old('department', $user->department) == 'CONSULTORIA JURIDICA' ? 'selected' : '' }}>CONSULTORIA JURIDICA</option>
                    <option value="LOGISTICA Y ALMACEN" {{ old('department', $user->department) == 'LOGISTICA Y ALMACEN' ? 'selected' : '' }}>LOGISTICA Y ALMACEN</option>
                    <option value="SERVICIOS GENERALES" {{ old('department', $user->department) == 'SERVICIOS GENERALES' ? 'selected' : '' }}>SERVICIOS GENERALES</option>
                    <option value="MENTE Y SALUD" {{ old('department', $user->department) == 'MENTE Y SALUD' ? 'selected' : '' }}>MENTE Y SALUD</option>
                    <option value="TECNOLOGIA DE LA INFORMACION" {{ old('department', $user->department) == 'TECNOLOGIA DE LA INFORMACION' ? 'selected' : '' }}>TECNOLOGIA DE LA INFORMACION</option>
                    <option value="FINANZAS" {{ old('department', $user->department) == 'FINANZAS' ? 'selected' : '' }}>FINANZAS</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="join_date">
                    Fecha de Ingreso
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="join_date" 
                    type="date" 
                    name="join_date" 
                    value="{{ old('join_date', $user->join_date ? $user->join_date->format('Y-m-d') : '') }}" 
                    required>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-[#1a4175] text-gray-100 font-bold py-2 px-4 rounded hover:bg-[#15345d] focus:outline-none focus:shadow-outline" 
                    type="submit">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
