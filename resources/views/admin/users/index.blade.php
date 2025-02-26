@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-[#1a4175] text-gray-100 px-4 py-2 rounded hover:bg-[#15345d]">
            Nuevo Usuario
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-[#1a4175] text-gray-100 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Identificación</th>
                    <th class="py-3 px-6 text-left">Ubicación</th>
                    <th class="py-3 px-6 text-left">Fecha de Ingreso</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $user->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                        <td class="py-3 px-6 text-left">{{ $user->identification }}</td>
                        <td class="py-3 px-6 text-left">{{ $user->location }}</td>
                        <td class="py-3 px-6 text-left">{{ $user->join_date ? $user->join_date->format('d/m/Y') : 'No especificada' }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('admin.users.edit', $user) }}" class="bg-[#1a4175] text-gray-100 px-3 py-1 rounded hover:bg-[#15345d] mx-1">
                                    Editar
                                </a>
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-gray-100 px-3 py-1 rounded hover:bg-red-700 mx-1" onclick="return confirm('¿Estás seguro de querer eliminar este usuario?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
