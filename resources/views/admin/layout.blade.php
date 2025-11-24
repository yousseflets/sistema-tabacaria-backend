<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Painel Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
<div class="flex">
    <aside class="w-64 bg-white shadow-md h-screen sticky top-0">
        <div class="p-6">
            <h2 class="text-xl font-bold">Admin</h2>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded hover:bg-gray-100">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="block py-2 px-3 rounded hover:bg-gray-100">Produtos</a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2 px-3 rounded hover:bg-gray-100">Categorias</a>
                <a href="{{ route('admin.profile.edit') }}" class="block py-2 px-3 rounded hover:bg-gray-100">Perfil</a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">@yield('title')</h1>
            <div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-red-500 text-white px-3 py-1 rounded">Sair</button>
                </form>
            </div>
        </header>

        <div>
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
