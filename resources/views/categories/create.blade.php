@extends('layouts.app')

@section('title', isset($category) ? 'Editar Categoria' : 'Nova Categoria')

@section('content')
    <h1>{{ isset($category) ? 'Editar Categoria' : 'Nova Categoria' }}</h1>

    <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input name="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}">
        </div>

        <button class="btn btn-primary">Salvar</button>
    </form>

@endsection
