<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Entrar — Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/logo-colors.css') }}" />
    <style>
        .glass { background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); }
        .input-icon { left: 12px; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-800 to-indigo-700 flex items-center justify-center" style="font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
    <div class="container mx-auto p-6">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left: Brand + Illustration -->
            <div class="hidden md:flex flex-col items-start justify-center text-white space-y-6">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-white/10 p-3">
                        <img src="{{ asset('logo.jpg') }}" alt="logo" class="w-16 h-16 rounded-full object-cover ring-4 ring-white/20" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">Sistema Tabacaria</h1>
                        <p class="text-sm opacity-90">Painel Administrativo — controle completo do catálogo</p>
                    </div>
                </div>

                <div class="glass p-6 rounded-2xl shadow-xl max-w-md">
                    <h3 class="text-white font-semibold">Controle rápido</h3>
                    <p class="text-sm text-white/90 mt-2">Importe CSV/XLSX, gerencie estoque e preços BRL com facilidade. Layout otimizado para produtividade.</p>
                    <div class="mt-4">
                        <ul class="text-sm text-white/80 space-y-2">
                            <li>• Importador robusto</li>
                            <li>• Filtros e relatórios</li>
                            <li>• Painel responsivo</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right: Login card -->
            <div class="mx-auto w-full max-w-md">
                <div class="bg-white rounded-3xl shadow-2xl p-8">
                    <div class="mb-6 text-center">
                        <h2 class="text-2xl font-semibold text-slate-800">Faça login na sua conta</h2>
                        <p class="text-sm text-slate-500 mt-1">Acesse o painel administrativo</p>
                    </div>

                    @if(session('error'))
                        <div class="mb-4 rounded p-3 bg-red-50 text-red-700">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5" novalidate>
                        @csrf

                        <div class="relative">
                            <label for="email" class="sr-only">Email</label>
                            <span class="absolute input-icon top-1/2 -translate-y-1/2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2.94 6.94A2 2 0 014 6h12a2 2 0 011.06.94L10 11 2.94 6.94z"/><path d="M18 8.118l-8 4.8-8-4.8V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nome@exemplo.com" class="pl-12 pr-4 py-3 w-full rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        </div>

                        <div class="relative">
                            <label for="password" class="sr-only">Senha</label>
                            <span class="absolute input-icon top-1/2 -translate-y-1/2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 8a5 5 0 0110 0v1h1a2 2 0 012 2v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5a2 2 0 012-2h1V8zm2 1V8a3 3 0 116 0v1H7z" clip-rule="evenodd"/></svg>
                            </span>
                            <input id="password" name="password" type="password" required placeholder="Senha" class="pl-12 pr-12 py-3 w-full rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                            <button type="button" id="togglePwd" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3C6 3 2.73 5.11 1 8c1.73 2.89 5 5 9 5s7.27-2.11 9-5c-1.73-2.89-5-5-9-5z"/><path d="M10 7a3 3 0 100 6 3 3 0 000-6z"/></svg>
                            </button>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 text-slate-600">
                                <input type="checkbox" name="remember" class="w-4 h-4">
                                Lembrar-me
                            </label>
                            <a href="#" class="text-indigo-600 hover:underline">Esqueceu a senha?</a>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow hover:scale-[1.01] transition transform">Entrar</button>

                        <div class="pt-2 text-center text-sm text-slate-400">ou entre com</div>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 py-2 rounded-xl border border-slate-200 flex items-center justify-center gap-2 text-sm hover:bg-slate-50">
                                  <img src="{{ asset('images/google.svg') }}" alt="Google" class="w-5 h-5 rounded-full object-cover"> Google
                            </button>
                            <button type="button" class="flex-1 py-2 rounded-xl border border-slate-200 flex items-center justify-center gap-2 text-sm hover:bg-slate-50">
                                  <img src="{{ asset('images/github.svg') }}" alt="GitHub" class="w-5 h-5 rounded-full object-cover"> GitHub
                            </button>
                        </div>
                    </form>

                    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} Sistema Tabacaria • Todos os direitos reservados</p>
                </div>
            </div>
        </div>
    </div>

    <script>
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

