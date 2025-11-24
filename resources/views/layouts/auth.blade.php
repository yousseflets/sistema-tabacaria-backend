<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login')</title>
    <link rel="stylesheet" href="/build/assets/app-B4y5Yth8.css">
    <style>
        html,body{height:100%;}
        body{background:#0b0b0b;color:#e6e6e6;font-family:Inter,ui-sans-serif,system-ui,sans-serif;margin:0;}
        .auth-center{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
        .card-auth{width:100%;max-width:1280px;border-radius:14px;overflow:hidden;display:flex;box-shadow:0 12px 40px rgba(0,0,0,.7);} 
        .card-left{flex:1;padding:56px 56px 56px 64px;background:#0f0f10;border-right:1px solid rgba(255,255,255,.03);display:flex;flex-direction:column;justify-content:center}
        .card-right{width:560px;background:linear-gradient(180deg,rgba(11,13,43,0.95),rgba(34,6,6,0.95));display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
        .brand{font-size:34px;font-weight:800;color:#ff3b3b;margin-bottom:18px}
        .logo-img{position:absolute;right:-10%;top:50%;transform:translateY(-50%) scale(1.45);width:auto;min-width:420px;height:120%;object-fit:cover;filter:contrast(110%) saturate(120%)}
        .logo-fallback{color:rgba(255,255,255,0.06);font-size:160px;font-weight:800;letter-spacing:6px}
        .lead{color:#bfbfbf;margin-bottom:20px}
        .form-control{width:100%;padding:.6rem .75rem;border-radius:6px;border:1px solid rgba(255,255,255,.08);background:#0f1314;color:#fff}
        .btn-primary{background:#ff3b3b;border:none;color:#fff;padding:.6rem 1rem;border-radius:6px}
        .muted{color:#9a9a9a;font-size:14px}
        a.link{color:#9fcfff}
    </style>
</head>
<body>
    <div class="auth-center">
        <div class="card-auth">
            @yield('card')
        </div>
    </div>
    <script src="/build/assets/app-CAiCLEjY.js"></script>
</body>
</html>
