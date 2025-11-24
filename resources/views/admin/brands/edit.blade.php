@extends('admin.layout')

@section('title', 'Editar Marca')

@section('content')
    <form method="POST" action="{{ route('admin.brands.update', $brand) }}" class="max-w-2x2 bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="nome" value="{{ old('nome', $brand->nome) }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Descrição</label>
            <textarea name="descricao" class="w-full border rounded px-3 py-2">{{ old('descricao', $brand->descricao) }}</textarea>
        </div>
        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Atualizar</button>
        </div>
    </form>
@endsection
