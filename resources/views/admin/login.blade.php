<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Entrar — Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/logo-colors.css') }}" />
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center" style="font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
    <div class="w-full max-w-3xl mx-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left: Illustration / Info -->
            <div class="hidden md:block">
                <div class="p-8 rounded-2xl bg-white shadow-md">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/logo.svg') }}" alt="logo" class="w-12 h-12 rounded" />
                        <div>
                            <div class="text-2xl font-bold">Sistema Tabacaria</div>
                            <div class="text-sm text-slate-500">Painel Administrativo</div>
                        </div>
                    </div>
                    <p class="mt-6 text-sm text-slate-600">Gerencie seu catálogo com importações fáceis, controle de estoque e preços em BRL. Interface limpa e responsiva construída com Flowbite.</p>
                </div>
            </div>

            <!-- Right: Flowbite form -->
            <div class="mx-auto w-full max-w-md">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-semibold mb-2">Bem-vindo de volta</h2>
                    <p class="text-sm text-slate-500 mb-4">Entre na sua conta administrativa</p>

                    @if(session('error'))
                        <div class="mb-4 rounded p-3 bg-red-50 text-red-700">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Seu email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nome@exemplo.com">
                        </div>

                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10" placeholder="Senha">
                                <button type="button" id="togglePwd" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">
                                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3C6 3 2.73 5.11 1 8c1.73 2.89 5 5 9 5s7.27-2.11 9-5c-1.73-2.89-5-5-9-5z"/><path d="M10 7a3 3 0 100 6 3 3 0 000-6z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="remember" class="w-4 h-4">
                                Lembrar-me
                            </label>
                            <a href="#" class="text-sm text-blue-600 hover:underline">Esqueceu a senha?</a>
                        </div>

                        <button type="submit" class="w-full text-white bg-gradient-to-r from-purple-600 to-blue-500 hover:opacity-95 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        (function(){
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('togglePwd');
            if (!pwd || !toggle) return;
            toggle.addEventListener('click', function(){
                if (pwd.type === 'password'){
                    pwd.type = 'text';
                    toggle.title = 'Ocultar senha';
                } else {
                    pwd.type = 'password';
                    toggle.title = 'Mostrar senha';
                }
            });
        })();
    </script>
</body>
</html>

