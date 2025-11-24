@extends('admin.layout')

@section('title', 'Editar Perfil')

@section('content')
    <form method="POST" action="{{ route('admin.profile.update') }}" class="max-w-x2 bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Senha (deixe em branco para manter)</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Confirme a Senha</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Salvar</button>
        </div>
    </form>
@endsection
