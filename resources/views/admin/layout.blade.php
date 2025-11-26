<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Painel Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Logout button color and hover */
        .logout-btn {
            background-color: #ef4444;
            transition: background-color 150ms ease-in-out, transform 120ms ease;
        }
        .logout-btn:hover {
            background-color: #950303ff; /* darker red on hover */
            transform: translateY(-1px);
        }
        .logout-btn:active { transform: translateY(0); }
    </style>
    {{-- Carrega variáveis de cores geradas a partir do logo (se existir) --}}
    <link rel="stylesheet" href="{{ asset('css/logo-colors.css') }}" />
    <!-- Alpine.js for small interactions (defer) -->
    <script defer src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <aside x-data="{ collapsed: (localStorage.getItem('sidebar_collapsed') === '1') }"
               x-init="$watch('collapsed', value => localStorage.setItem('sidebar_collapsed', value ? '1' : '0'))"
               :class="collapsed ? 'w-20' : 'w-72'"
               class="bg-white shadow-lg h-screen sticky top-0 transition-all duration-200 overflow-hidden">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color:var(--logo-primary,#4f46e5);color:var(--logo-on-primary,#fff)" class="w-10 h-10 rounded flex items-center justify-center font-bold">
                            @if(file_exists(public_path('logo.jpg')))
                                <a href="{{ route('admin.dashboard') }}">
                                    <img src="{{ asset('logo.jpg') }}" alt="logo" class="w-10 h-10 object-cover rounded" />
                                </a>
                            @else
                                ST
                            @endif
                        </div>
                        <div x-bind:class="collapsed ? 'hidden' : 'block'">
                            <div class="text-lg font-semibold">Sistema Tabacaria</div>
                            <div class="text-xs text-gray-500">Painel Administrativo</div>
                        </div>
                    </div>
                    <button @click="collapsed = !collapsed" class="text-gray-500 hover:text-gray-700 p-1 rounded-md focus:outline-none" :aria-expanded="collapsed ? 'true' : 'false'" title="Abrir/Fechar menu">
                        <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3 5h14M3 10h14M3 15h14"/></svg>
                        <svg x-show="collapsed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 6l8 4-8 4V6z"/></svg>
                    </button>
                </div>

                <nav class="flex-1 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-semibold' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                        <span x-bind:class="collapsed ? 'hidden' : ''" x-bind:title="collapsed ? 'Dashboard' : ''">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 font-semibold' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7"/></svg>
                        <span x-bind:class="collapsed ? 'hidden' : ''" x-bind:title="collapsed ? 'Produtos' : ''">Produtos</span>
                    </a>

                    <div class="mt-4 px-3 text-xs text-gray-400 uppercase" x-bind:class="collapsed ? 'hidden' : ''">Configuração</div>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors duration-150 {{ request()->routeIs('admin.brands.*') ? 'bg-gray-100 font-semibold' : 'text-gray-700' }}">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM6 20a6 6 0 0112 0"/></svg>
                            <span x-bind:class="collapsed ? 'hidden' : ''" x-bind:title="collapsed ? 'Marcas' : ''">Marcas</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 font-semibold' : 'text-gray-700' }}">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                            <span x-bind:class="collapsed ? 'hidden' : ''" x-bind:title="collapsed ? 'Categorias' : ''">Categorias</span>
                        </a>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors duration-150 {{ request()->routeIs('admin.profile.*') ? 'bg-gray-100 font-semibold' : 'text-gray-700' }}">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 15a4 4 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span x-bind:class="collapsed ? 'hidden' : ''">Perfil</span>
                        </a>
                    </div>
                </nav>

                <div class="mt-auto">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                            <button
                                type="submit"
                                aria-label="Sair"
                                :title="collapsed ? 'Sair' : ''"
                                :class="collapsed ? 'mx-auto mb-2 w-10 h-10 p-2 rounded-md text-white flex items-center justify-center' : 'w-full px-3 py-2 rounded-md text-white flex items-center justify-between'"
                                class="logout-btn">
                                <span x-bind:class="collapsed ? 'hidden' : 'ml-1 font-medium'">Sair</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 2v10" />
                                    <path d="M4.93 4.93a10 10 0 0114.14 0 10 10 0 11-14.14 0z" />
                                </svg>
                            </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                    <button type="button" onclick="history.back()" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-3 py-2 rounded shadow-sm hover:bg-gray-50" title="Voltar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4" fill="currentColor" aria-hidden="true" focusable="false"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 288 480 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-370.7 0 105.4-105.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
                    </button>
                    <h1 class="text-2xl font-semibold">@yield('title')</h1>
                </div>
                <div class="flex items-center gap-4">
                    @if(session('error'))
                        <div class="bg-red-100 text-red-700 p-2 rounded">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-2 rounded">{{ session('success') }}</div>
                    @endif
                </div>
            </header>

            <div>
                @yield('content')
            </div>
        </main>
    </div>
</div>
</div>
<!-- Flowbite components (requires Tailwind) -->
<script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
<!-- Chart.js for charts on dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@yield('scripts')
</body>
</html>
