@extends('admin.layout')

@section('title', 'Marcas')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-4">
        <h2 class="text-lg font-medium">Lista de Marcas</h2>
        <div class="flex items-center gap-3">
            <form id="brands-filter-form" method="GET" action="{{ route('admin.brands.index') }}" class="flex items-center gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar marcas" class="block p-2 border rounded-md text-sm" />
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">Pesquisar</button>
                <a href="{{ route('admin.brands.index') }}" class="px-3 py-2 bg-gray-100 rounded-md text-sm clear-filters">Limpar</a>
            </form>
            <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">Nova Marca</a>
        </div>
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
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="bg-blue-50 text-blue-600 p-2 rounded mr-3 inline-flex items-center hover:bg-blue-100" title="Editar marca">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5" fill="currentColor" aria-hidden="true" focusable="false">
                                    <path d="M36.4 353.2c4.1-14.6 11.8-27.9 22.6-38.7l181.2-181.2 33.9-33.9c16.6 16.6 51.3 51.3 104 104l33.9 33.9-33.9 33.9-181.2 181.2c-10.7 10.7-24.1 18.5-38.7 22.6L30.4 510.6c-8.3 2.3-17.3 0-23.4-6.2S-1.4 489.3 .9 481L36.4 353.2zm55.6-3.7c-4.4 4.7-7.6 10.4-9.3 16.6l-24.1 86.9 86.9-24.1c6.4-1.8 12.2-5.1 17-9.7L91.9 349.5zm354-146.1c-16.6-16.6-51.3-51.3-104-104L308 65.5C334.5 39 349.4 24.1 352.9 20.6 366.4 7 384.8-.6 404-.6S441.6 7 455.1 20.6l35.7 35.7C504.4 69.9 512 88.3 512 107.4s-7.6 37.6-21.2 51.1c-3.5 3.5-18.4 18.4-44.9 44.9z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="inline" onsubmit="return confirm('Excluir marca?');">
                                @csrf
                                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded inline-flex items-center" title="Excluir marca">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        @if($brands->total() > 0)
            <div class="text-sm text-gray-600 mb-2">Mostrando {{ $brands->firstItem() }} a {{ $brands->lastItem() }} de {{ $brands->total() }} resultados</div>
        @else
            <div class="text-sm text-gray-600 mb-2">Nenhum resultado encontrado</div>
        @endif
        {{ $brands->links() }}
    </div>

@section('scripts')
<div id="loadingOverlay" class="hidden fixed inset-0 bg-white/75 z-50 flex items-center justify-center">
    <div class="text-center">
        <svg class="animate-spin h-10 w-10 text-gray-700 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <div class="mt-2 text-gray-700">Carregando...</div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var overlay = document.getElementById('loadingOverlay');
    var form = document.getElementById('brands-filter-form');
    if (form) form.addEventListener('submit', function(){ if (overlay) overlay.classList.remove('hidden'); });
    var clears = document.querySelectorAll('.clear-filters');
    clears.forEach(function(a){ a.addEventListener('click', function(){ if (overlay) overlay.classList.remove('hidden'); }); });
});
</script>
@endsection
@endsection
