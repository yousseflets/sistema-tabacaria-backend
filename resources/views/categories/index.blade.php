@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Categorias</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Nova Categoria</a>
 </div>

 <table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Slug</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td class="text-end">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-secondary">Editar</a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Apagar</button></form>
            </td>
        </tr>
        @endforeach
    </tbody>
 </table>

{{ $categories->links() }}

@endsection
