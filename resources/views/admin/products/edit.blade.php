@extends('admin.layout')

@section('title', 'Editar Produto')

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="max-w-2x2 bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Categoria</label>
            <select name="category_id" class="w-full border rounded px-3 py-2">
                <option value="">— Nenhuma —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Preço</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Descrição</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Atualizar</button>
        </div>
    </form>
@endsection
