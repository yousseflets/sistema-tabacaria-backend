<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Painel Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Carrega variáveis de cores geradas a partir do logo (se existir) --}}
    <link rel="stylesheet" href="{{ asset('css/logo-colors.css') }}" />
</head>
<body class="bg-gray-100 min-h-screen font-sans">
<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <aside class="w-72 bg-white shadow-lg h-screen sticky top-0">
            <div class="p-6 flex flex-col h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div style="background-color:var(--logo-primary,#4f46e5);color:var(--logo-on-primary,#fff)" class="w-10 h-10 rounded flex items-center justify-center font-bold">
                        {{-- Logo: se existir, mostramos a imagem; caso contrário, mostramos iniciais --}}
                        @if(file_exists(public_path('logo.jpg')))
                            <img src="{{ asset('logo.jpg') }}" alt="logo" class="w-10 h-10 object-cover rounded" />
                        @else
                            ST
                        @endif
                    </div>
                    <div>
                        <div class="text-lg font-semibold">Sistema Tabacaria</div>
                        <div class="text-xs text-gray-500">Painel Administrativo</div>
                    </div>
                </div>

                <nav class="flex-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 mt-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7"/></svg>
                        Produtos
                    </a>

                    <div class="mt-4 px-4 text-xs text-gray-400 uppercase">Configuração</div>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM6 20a6 6 0 0112 0"/></svg>
                            Marcas
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                            Categorias
                        </a>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 15a4 4 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Perfil
                        </a>
                    </div>
                </nav>

                <div class="mt-auto">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" style="background-color:var(--logo-accent,#ef4444);color:var(--logo-on-primary,#fff)" class="w-full text-left px-4 py-2 rounded-md">Sair</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">@yield('title')</h1>
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
@yield('scripts')
</body>
</html>
