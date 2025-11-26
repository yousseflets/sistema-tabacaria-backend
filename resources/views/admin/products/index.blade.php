@extends('admin.layout')

@section('title', 'Produtos')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-medium">Lista de Produtos</h2>
        </div>
        <div class="flex items-center gap-3">
            <button data-modal-target="importModal" data-modal-toggle="importModal" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-orange-100 text-gray-800 hover:bg-orange-200" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-4 h-4" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M64 0C28.7 0 0 28.7 0 64l0 240 182.1 0-31-31c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l72 72c9.4 9.4 9.4 24.6 0 33.9l-72 72c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l31-31-182.1 0 0 96c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-277.5c0-17-6.7-33.3-18.7-45.3L258.7 18.7C246.7 6.7 230.5 0 213.5 0L64 0zM325.5 176L232 176c-13.3 0-24-10.7-24-24L208 58.5 325.5 176z"/>
                </svg>
                <span>Importar Produtos</span>
            </button>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4 h-4" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 160-160 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32l0-160 160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0 0-160z"/>
                </svg>
                <span>Novo Produto</span>
            </a>
        </div>
    </div>

    <!-- Filter panel -->
    <form id="products-filter-form" method="GET" action="{{ route('admin.products.index') }}" class="bg-white rounded p-4 mb-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Pesquisar</label>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Nome do produto" class="block w-full p-2 border rounded-md text-sm" />
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Categoria</label>
                <select name="category_id" class="block w-full p-2 border rounded-md text-sm">
                    <option value="">Todas</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Marca</label>
                <select name="brand_id" class="block w-full p-2 border rounded-md text-sm">
                    <option value="">Todas</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status" class="block w-full p-2 border rounded-md text-sm">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                </select>
            </div>
        </div>

                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Preço mínimo</label>
                <input type="text" name="price_min" value="{{ request('price_min') }}" placeholder="0,00" class="block w-full p-2 border rounded-md text-sm" />
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Preço máximo</label>
                <input type="text" name="price_max" value="{{ request('price_max') }}" placeholder="0,00" class="block w-full p-2 border rounded-md text-sm" />
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Filtrar</button>
                <a href="{{ route('admin.products.index') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-100 rounded-md clear-filters">Limpar</a>
            </div>
            <div class="flex items-end text-sm text-gray-500">
                <span>Resultados: <strong>{{ $products->total() }}</strong></span>
            </div>
        </div>
    </form>

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
                        <td class="px-6 py-4">
                            @if($product->active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">Ativo</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-semibold">Inativo</span>
                            @endif
                        </td>
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
        @if($products->total() > 0)
            <div class="text-sm text-gray-600 mb-2">Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ $products->total() }} resultados</div>
        @else
            <div class="text-sm text-gray-600 mb-2">Nenhum resultado encontrado</div>
        @endif
        {{ $products->links() }}
    </div>
    
        <!-- Import modal -->
        <div id="importModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-md h-full md:h-auto mx-auto">
                <div class="relative bg-white rounded-lg shadow">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="importModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Fechar modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900">Importar Produtos (CSV / XLSX)</h3>
                        <form class="space-y-6" action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-900">Arquivo</label>
                                        <input type="file" name="file" accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" />
                                </div>
                                <div class="text-sm text-gray-500">Formato esperado: cabeçalho em português, separador ponto-e-vírgula (;) ou XLSX.</div>
                                <div class="flex justify-end pt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Enviar importação</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const inputs = document.querySelectorAll('input[name="price_min"], input[name="price_max"]');
    const formatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    function parseLocalized(v){
        if(!v) return NaN;
        v = String(v).replace(/[^0-9,\.\-]/g,'').trim();
        if(v === '') return NaN;
        if(v.indexOf('.') !== -1 && v.indexOf(',') !== -1){
            v = v.replace(/\./g, '').replace(',', '.');
        } else if(v.indexOf(',') !== -1){
            v = v.replace(',', '.');
        }
        if(v === '' || v === '-') return NaN;
        return parseFloat(v);
    }

    inputs.forEach(function(input){
        // initial format if value present
        if(input.value){
            const n = parseLocalized(input.value);
            if(!isNaN(n)) input.value = formatter.format(n);
        }

        input.addEventListener('focus', function(){
            const n = parseLocalized(this.value);
            this.dataset.raw = this.value;
            this.value = isNaN(n) ? '' : n;
        });

        input.addEventListener('blur', function(){
            const n = parseLocalized(this.value);
            this.value = isNaN(n) ? '' : formatter.format(n);
        });
    });

    const filterForm = document.querySelector('form[action=\'{{ route('admin.products.index') }}\']');
    if(filterForm){
        filterForm.addEventListener('submit', function(){
            inputs.forEach(function(input){
                let v = String(input.value).replace(/[^0-9,\.\-]/g,'').trim();
                if(v === '') { input.value = ''; return; }
                if(v.indexOf('.') !== -1 && v.indexOf(',') !== -1){
                    v = v.replace(/\./g, '').replace(',', '.');
                } else {
                    v = v.replace(',', '.');
                }
                input.value = v;
            });
        });
    }
});
</script>
<!-- Loading overlay (hidden by default) -->
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
// Ao detectar que a navegação foi por reload (F5/CTRL+R), mostrar loading e limpar querystring
try {
    (function(){
        var navType = '';
        var entries = performance.getEntriesByType && performance.getEntriesByType('navigation');
        if (entries && entries.length && entries[0].type) {
            navType = entries[0].type; // 'reload', 'navigate', etc.
        } else if (performance.navigation && performance.navigation.type === 1) {
            navType = 'reload'; // older browsers
        }

        if (navType === 'reload' && window.location.search) {
            // show overlay (if present) to indicate loading on F5 only when we will redirect (clear querystring)
            try {
                var overlayEl = document.getElementById('loadingOverlay');
                if (overlayEl) overlayEl.classList.remove('hidden');
                // safety: hide overlay after 3s if navigation for some reason doesn't happen
                setTimeout(function(){ if (overlayEl && !overlayEl.classList.contains('hidden')) overlayEl.classList.add('hidden'); }, 3000);
            } catch (e) {
                // ignore
            }

            // clear querystring after a tiny delay so overlay can render
            var newUrl = window.location.pathname + (window.location.hash || '');
            setTimeout(function(){
                // Use replace para não poluir o histórico
                window.location.replace(newUrl);
            }, 50);
        }
    })();
} catch (e) {
    // silencioso em caso de erro
}

// Mostra o overlay de loading quando o formulário de filtro é enviado ou o link de limpar é clicado
document.addEventListener('DOMContentLoaded', function(){
    var overlay = document.getElementById('loadingOverlay');
    var form = document.getElementById('products-filter-form');
    if (form) {
        form.addEventListener('submit', function(){
            if (overlay) overlay.classList.remove('hidden');
        });
    }

    var clears = document.querySelectorAll('.clear-filters');
    clears.forEach(function(a){
        a.addEventListener('click', function(){
            if (overlay) overlay.classList.remove('hidden');
            // permitir navegação normal
        });
    });
});
</script>
@endsection
