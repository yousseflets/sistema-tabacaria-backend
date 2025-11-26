@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-slate-500">Visão geral do catálogo e indicadores principais</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('admin.products.index') }}" class="btn-brand">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a2 2 0 00-2 2v6H6l4 4 4-4h-2V4a2 2 0 00-2-2z"/></svg>
                Ir para Produtos
            </a>
        </div>
    </div>

    <div class="mt-6">
        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-lg font-semibold mb-3">Distribuição de produtos</h3>
            <div class="w-full" style="height:220px">
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-indigo-100 to-indigo-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10l-6-3-6 3V5z"/></svg>
            </div>
            <div>
                <div class="text-sm text-slate-500">Total de produtos</div>
                <div class="text-4xl font-extrabold text-slate-900">{{ $total }}</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <div class="text-sm text-slate-500">Ativos</div>
                <div class="text-4xl font-extrabold text-success">{{ $active }}</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" viewBox="0 0 20 20" fill="currentColor"><path d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l5.516 9.82A2 2 0 0115.516 17H4.484a2 2 0 01-1.742-2.081l5.515-9.82z"/></svg>
            </div>
            <div>
                <div class="text-sm text-slate-500">Inativos</div>
                <div class="text-4xl font-extrabold text-danger">{{ $inactive }}</div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-lg font-semibold mb-3">Visão rápida</h3>
            <p class="text-sm text-slate-600">Acompanhe o total de produtos e status de disponibilidade. Use a seção de Produtos para gerenciar entradas, imagens e importações.</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-lg font-semibold mb-3">Ações rápidas</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.products.create') }}" class="bg-green-200 inline-flex items-center gap-2 border border-slate-200 px-3 py-2 rounded hover:shadow">
                    Cadastrar Produto
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-yellow-200 inline-flex items-center gap-2 border border-slate-200 px-3 py-2 rounded hover:shadow">
                    Categorias
                </a>
                <a href="{{ route('admin.brands.index') }}" class="bg-orange-200 inline-flex items-center gap-2 border border-slate-200 px-3 py-2 rounded hover:shadow">
                    Marcas
                </a>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-md lg:col-span-2">
            <h3 class="text-lg font-semibold mb-3">Produtos adicionados (últimos 6 meses)</h3>
            <div class="w-full" style="height:260px">
                <canvas id="lineProducts"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-lg font-semibold mb-3">Últimos produtos</h3>
            <div class="divide-y divide-gray-100">
                @foreach($latestProducts as $p)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($p->image)
                                <img src="{{ asset('storage/' . ltrim($p->image, '/')) }}" alt="{{ $p->name }}" class="w-10 h-10 object-cover rounded" />
                            @else
                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center text-sm text-gray-500">—</div>
                            @endif
                            <div>
                                <div class="text-sm font-medium">{{ Str::limit($p->name, 40) }}</div>
                                <div class="text-xs text-gray-500">{{ $p->category ? $p->category->nome : '—' }} • {{ $p->brand ? $p->brand->nome : '—' }}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">{{ $p->created_at->format('d/m H:i') }}</div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-2xl p-6 shadow-md">
        <h3 class="text-lg font-semibold mb-3">Categorias x Quantidade</h3>
        <div class="w-full" style="height:320px">
            <canvas id="quantityChart"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const ctx = document.getElementById('productsChart');
            if (!ctx) return;
            const totalProducts = {{ (int) $total }};

            // Plugin to draw center text
            const centerText = {
                id: 'centerText',
                beforeDraw(chart) {
                    const {ctx, width, height} = chart;
                    ctx.save();
                    const fontSize = Math.min(width, height) / 10;
                    ctx.font = `600 ${fontSize}px sans-serif`;
                    ctx.fillStyle = '#111827';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(totalProducts.toString(), width / 2, height / 2 - 6);
                    ctx.font = `400 ${Math.max(12, fontSize/2)}px sans-serif`;
                    ctx.fillStyle = '#6b7280';
                    ctx.fillText('Produtos', width / 2, height / 2 + Math.max(12, fontSize/2));
                    ctx.restore();
                }
            };

            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Ativos', 'Inativos'],
                    datasets: [{
                        data: [{{ (int) $active }}, {{ (int) $inactive }}],
                        backgroundColor: ['#22c55e', '#ef4444'],
                        hoverBackgroundColor: ['#16a34a', '#dc2626'],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    animation: { easing: 'easeOutCirc' },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const sum = context.chart._metasets ? context.chart._metasets[context.datasetIndex].total : ({{ (int) $active }} + {{ (int) $inactive }});
                                    const perc = sum ? Math.round((value / sum) * 100) : 0;
                                    return context.label + ': ' + value + ' (' + perc + '%)';
                                }
                            }
                        }
                    }
                },
                plugins: [centerText]
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const lineCtx = document.getElementById('lineProducts');
            if (!lineCtx) return;
            const months = @json($months);
            const counts = @json($counts);

            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Produtos criados',
                        data: counts,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { precision:0 } }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const qCtx = document.getElementById('quantityChart');
            if (!qCtx) return;
            const labels = @json($categoryNames ?? []);
            const data = @json($categoryTotals ?? []);

            new Chart(qCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantidade em estoque',
                        data: data,
                        backgroundColor: labels.map((_,i)=> i%2? 'rgba(59,130,246,0.9)' : 'rgba(16,185,129,0.9)'),
                        borderColor: 'rgba(255,255,255,0.0)',
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { ticks: { maxRotation:45, minRotation:0 }, beginAtZero: true },
                        y: { beginAtZero: true }
                    },
                    plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } }
                }
            });
        });
    </script>
@endsection
