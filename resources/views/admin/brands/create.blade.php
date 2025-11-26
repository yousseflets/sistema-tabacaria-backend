@extends('admin.layout')

@section('title', 'Criar Marca')

@section('content')
    <form method="POST" action="{{ route('admin.brands.store') }}" class="max-w-3xl bg-white p-6 rounded-lg shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="nome" value="{{ old('nome') }}" required class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Descrição</label>
            <textarea name="descricao" class="block w-full p-2.5 bg-white border border-gray-300 rounded-lg text-sm">{{ old('descricao') }}</textarea>
        </div>
        <div class="flex justify-end">
            <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Salvar</button>
        </div>
    </form>
@endsection
