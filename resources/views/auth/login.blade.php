@extends('layouts.app')

@section('title', 'Login — HD PSB')

@push('styles')
<style>
  body{background:linear-gradient(135deg,#0F1E33,#1E3A5F,#0D2440);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
  .login-card{background:rgba(255,255,255,0.04);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,0.10);border-radius:20px;padding:2.5rem 2.25rem;width:100%;max-width:400px;box-shadow:0 32px 80px rgba(0,0,0,0.4)}
  .login-logo{display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:2rem}
  .login-logo-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,var(--tg-blue),#1A8FCC);display:flex;align-items:center;justify-content:center}
  .login-logo-icon svg{width:26px;height:26px;fill:white}
  .login-logo-text{color:white}.login-logo-text h1{font-size:22px;font-weight:700}
  .login-label{font-size:11px;font-weight:600;color:rgba(255,255,255,0.5);letter-spacing:.08em;text-transform:uppercase;margin-bottom:7px;display:block}
  .login-field{position:relative;margin-bottom:1rem}
  .login-field-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:16px;height:16px;opacity:0.4;pointer-events:none}
  .login-inp{width:100%;padding:12px 14px 12px 42px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:10px;color:white;font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;outline:none}
  .login-inp::placeholder{color:rgba(255,255,255,0.3)}
  .login-inp:focus{border-color:var(--tg-blue);background:rgba(42,171,238,0.08)}
  .login-inp option{background:#1E3A5F;color:white}
  .login-btn{width:100%;padding:13px;margin-top:0.5rem;background:linear-gradient(135deg,var(--tg-blue),var(--tg-dark));border:none;border-radius:10px;color:white;font-size:14px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer}
  .login-btn:hover{opacity:0.9}
  .login-err{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:10px 14px;color:#FCA5A5;font-size:13px;text-align:center;margin-top:1rem}
</style>
@endpush

@section('content')
<div class="login-card">
  <div class="login-logo">
    <div class="login-logo-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8l-1.82 8.55c-.13.62-.49.77-.99.48l-2.75-2.03-1.33 1.28c-.15.15-.27.27-.55.27l.19-2.78 5.01-4.52c.22-.19-.05-.3-.33-.11L7.08 14.36l-2.72-.85c-.59-.18-.6-.59.12-.87l10.63-4.1c.49-.18.92.12.73.26z"/></svg>
    </div>
    <div class="login-logo-text"><h1>HELPDESK PROVISIONING</h1></div>
  </div>

  {{-- Tampilkan error validasi --}}
  @if ($errors->has('login'))
    <div class="login-err">{{ $errors->first('login') }}</div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf

    <label class="login-label">Pilih Akun</label>
    <div class="login-field">
      <svg class="login-field-icon" viewBox="0 0 24 24" fill="currentColor" style="color:white"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
      <select class="login-inp" name="username" required>
        <option value="">-- Pilih nama --</option>
        @foreach (['ADMIN','ANJAS','JHON','LINA','NANDA','PUTRI','TAUFIQ','TIKA','IRVAN','JULIARDI','RITA'] as $nama)
          <option value="{{ $nama }}" {{ old('username') === $nama ? 'selected' : '' }}>{{ $nama }}</option>
        @endforeach
      </select>
    </div>

    <label class="login-label">Password</label>
    <div class="login-field">
      <svg class="login-field-icon" viewBox="0 0 24 24" fill="currentColor" style="color:white"><path d="M18 8h-1V6c0-2.8-2.2-5-5-5S7 3.2 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.7 1.4-3.1 3.1-3.1 1.7 0 3.1 1.4 3.1 3.1v2z"/></svg>
      <input class="login-inp" type="password" name="password" placeholder="Masukkan password..." autocomplete="current-password" required>
    </div>

    <button type="submit" class="login-btn">Masuk</button>
  </form>
</div>
@endsection
