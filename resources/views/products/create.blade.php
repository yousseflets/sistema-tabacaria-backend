@extends('layouts.app')

@section('title', isset($product) ? 'Editar Produto' : 'Novo Produto')

@section('content')
    <h1>{{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}</h1>

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input name="price" class="form-control" value="{{ old('price', $product->price ?? '0.00') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input name="quantity" class="form-control" value="{{ old('quantity', $product->quantity ?? 0) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="category_id" class="form-select">
                <option value="">-- Nenhuma --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <button class="btn btn-primary">Salvar</button>
    </form>

@endsection
