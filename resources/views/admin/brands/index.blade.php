@extends('admin.layout')

@section('title', 'Marcas')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium">Lista de Marcas</h2>
        <a href="{{ route('admin.brands.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nova Marca</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($brands as $brand)
                    <tr>
                        <td class="px-6 py-4">{{ $brand->nome }}</td>
                        <td class="px-6 py-4">{{ Str::limit($brand->descricao, 100) }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="text-blue-600 mr-3">Editar</a>
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="inline" onsubmit="return confirm('Excluir marca?');">
                                @csrf
                                <button class="text-sm bg-gray-200 px-2 py-1 rounded">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $brands->links() }}
    </div>
@endsection
