@extends('admin.layout')

@section('title', 'Produtos')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium">Lista de Produtos</h2>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Novo Produto</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4">{{ $product->category ? $product->category->nome : '—' }}</td>
                        <td class="px-6 py-4">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $product->active ? 'Ativo' : 'Inativo' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 mr-3">Editar</a>
                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                                @csrf
                                <button class="text-sm bg-gray-200 px-2 py-1 rounded">{{ $product->active ? 'Desativar' : 'Ativar' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
