<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'HD PSB')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy:#1E3A5F;--bg:#F0F3F8;--white:#FFFFFF;--border:#DDE3ED;
    --text:#1A202C;--muted:#6B7280;--green:#0A6E50;--green-bg:#D1FAE5;
    --orange:#B45309;--orange-bg:#FEF3C7;--red:#991B1B;--red-bg:#FEE2E2;
    --blue:#1D4ED8;--blue-bg:#DBEAFE;--tg-blue:#2AABEE;--tg-dark:#1A8FCC;
    --shadow-sm:0 1px 3px rgba(0,0,0,.08);--shadow-md:0 4px 16px rgba(0,0,0,.10);
    --shadow-lg:0 20px 60px rgba(0,0,0,.18);--radius:10px;--radius-lg:14px;
  }
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);font-size:14px}
</style>
@stack('styles')
</head>
<body>
@yield('content')
@stack('scripts')
</body>
</html>
