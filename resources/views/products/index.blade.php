@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Produtos</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Novo Produto</a>
 </div>

 <table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? '-' }}</td>
            <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
            <td>{{ $product->quantity }}</td>
            <td class="text-end">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-secondary">Editar</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Apagar</button></form>
            </td>
        </tr>
        @endforeach
    </tbody>
 </table>

{{ $products->links() }}

@endsection
