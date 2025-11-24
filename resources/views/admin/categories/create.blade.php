@extends('admin.layout')

@section('title', 'Criar Categoria')

@section('content')
    <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-2x2 bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="nome" value="{{ old('nome') }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Descrição</label>
            <textarea name="descricao" class="w-full border rounded px-3 py-2">{{ old('descricao') }}</textarea>
        </div>
        <div class="flex justify-end">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
        </div>
    </form>
@endsection
