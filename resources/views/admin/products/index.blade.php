@extends('admin.layout')

@section('title', 'Produtos')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium">Lista de Produtos</h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.import.form') }}" class="bg-orange-100 text-gray-800 px-3 py-2 rounded border inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5 mr-2 inline-block" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M64 0C28.7 0 0 28.7 0 64l0 240 182.1 0-31-31c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l72 72c9.4 9.4 9.4 24.6 0 33.9l-72 72c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l31-31-182.1 0 0 96c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-277.5c0-17-6.7-33.3-18.7-45.3L258.7 18.7C246.7 6.7 230.5 0 213.5 0L64 0zM325.5 176L232 176c-13.3 0-24-10.7-24-24L208 58.5 325.5 176z"/>
                </svg>
                <span>Importar Produtos</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5 mr-2 inline-block" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 160-160 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32l0-160 160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0 0-160z"/>
                </svg>
                <span>Novo Produto</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagem</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . ltrim($product->image, '/')) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22128%22 height=%22128%22 viewBox=%220 0 24 24%22 fill=%22%23f3f4f6%22%3E%3Crect width=%2224%22 height=%2224%22 rx=%224%22 /%3E%3Cpath d=%22M7 8h10M7 12h10M7 16h6%22 stroke=%22%23cbd5e1%22 stroke-width=%221%22 stroke-linecap=%22round%22/%3E%3C/svg%3E';" />
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4">{{ $product->quantity ?? 0 }}</td>
                        <td class="px-6 py-4">{{ $product->brand ? $product->brand->nome : '—' }}</td>
                        <td class="px-6 py-4">{{ $product->category ? $product->category->nome : '—' }}</td>
                        <td class="px-6 py-4">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $product->active ? 'Ativo' : 'Inativo' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-50 text-blue-600 p-2 rounded mr-3 inline-flex items-center hover:bg-blue-100" title="Editar produto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5" fill="currentColor" aria-hidden="true" focusable="false">
                                    <path d="M36.4 353.2c4.1-14.6 11.8-27.9 22.6-38.7l181.2-181.2 33.9-33.9c16.6 16.6 51.3 51.3 104 104l33.9 33.9-33.9 33.9-181.2 181.2c-10.7 10.7-24.1 18.5-38.7 22.6L30.4 510.6c-8.3 2.3-17.3 0-23.4-6.2S-1.4 489.3 .9 481L36.4 353.2zm55.6-3.7c-4.4 4.7-7.6 10.4-9.3 16.6l-24.1 86.9 86.9-24.1c6.4-1.8 12.2-5.1 17-9.7L91.9 349.5zm354-146.1c-16.6-16.6-51.3-51.3-104-104L308 65.5C334.5 39 349.4 24.1 352.9 20.6 366.4 7 384.8-.6 404-.6S441.6 7 455.1 20.6l35.7 35.7C504.4 69.9 512 88.3 512 107.4s-7.6 37.6-21.2 51.1c-3.5 3.5-18.4 18.4-44.9 44.9z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                                @csrf
                                @if($product->active)
                                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded inline-flex items-center justify-center" title="Desativar produto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v10m0 8a8 8 0 100-16 8 8 0 000 16z" />
                                        </svg>
                                    </button>
                                @else
                                    <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded inline-flex items-center justify-center" title="Ativar produto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v10m0 8a8 8 0 100-16 8 8 0 000 16z" />
                                        </svg>
                                    </button>
                                @endif
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
