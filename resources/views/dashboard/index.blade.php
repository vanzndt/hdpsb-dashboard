@'
@extends('layouts.app')

@section('title', 'Dashboard — HD PSB')

@push('styles')
<style>
  .topbar{background:var(--navy);color:#fff;padding:0 1.5rem;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,0.25)}
  .brand{display:flex;align-items:center;gap:10px;font-size:15px;font-weight:700}
  .brand svg{width:22px;height:22px;fill:var(--tg-blue)}
  .topbar-right{display:flex;align-items:center;gap:10px}
  .badge-live{display:flex;align-items:center;gap:6px;font-size:12px;background:rgba(255,255,255,0.10);padding:4px 12px;border-radius:20px}
  .dot{width:7px;height:7px;border-radius:50%;background:#4ADE80;animation:blink 1.4s infinite}
  @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
  .conn-status{font-size:11px;padding:3px 10px;border-radius:20px}
  .conn-ok{background:rgba(74,222,128,0.15);color:#4ADE80}
  .conn-err{background:rgba(239,68,68,0.15);color:#FCA5A5}
  .user-badge{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.10);border:1px solid rgba(255,255,255,0.12);border-radius:24px;padding:4px 12px 4px 6px}
  .user-avatar{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0}
  .user-name{font-size:12px;font-weight:600}.user-role{font-size:10px;opacity:0.6}
  .logout-btn{background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.7);border-radius:8px;padding:4px 10px;font-size:11px;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif}
  .logout-btn:hover{background:rgba(239,68,68,0.25);color:#FCA5A5}
  .role-banner{background:linear-gradient(90deg,#0F4C81,#1565C0);color:white;font-size:12px;font-weight:600;padding:8px 1.5rem;display:flex;align-items:center;gap:8px;border-bottom:1px solid rgba(255,255,255,0.1)}
  .main{padding:1.25rem 1.5rem;max-width:1340px;margin:0 auto}
  .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-bottom:1.25rem}
  .stat{background:var(--white);border-radius:var(--radius);padding:1rem 1.1rem;border:1px solid var(--border);box-shadow:var(--shadow-sm)}
  .stat-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;font-weight:600}
  .stat-val{font-size:28px;font-weight:700;line-height:1}
  .toolbar{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:.7rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:10px;flex-wrap:wrap;position:sticky;top:56px;z-index:50;box-shadow:var(--shadow-sm)}
  .search-wrap{position:relative;flex:1;min-width:180px}
  .search-wrap svg{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--muted)}
  .search-input{width:100%;padding:8px 10px 8px 34px;border:1px solid var(--border);border-radius:8px;font-size:13px;background:var(--bg);color:var(--text);outline:none;font-family:'Plus Jakarta Sans',sans-serif}
  .filter-group{display:flex;gap:5px;flex-wrap:wrap}
  .fbtn{font-size:12px;padding:5px 14px;border-radius:20px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-weight:500;white-space:nowrap}
  .fbtn.active{background:var(--navy);color:#fff;border-color:var(--navy)}
  .table-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm)}
  .table-header{padding:.8rem 1rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
  .table-title{font-size:13px;font-weight:700}
  .count-badge{font-size:11px;background:var(--bg);color:var(--muted);padding:2px 10px;border-radius:20px;font-weight:600}
  .tbl-wrap{overflow-x:auto}
  table{width:100%;border-collapse:collapse;min-width:820px}
  thead th{text-align:left;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);padding:9px 12px;background:#F8FAFC;border-bottom:1px solid var(--border);white-space:nowrap}
  tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
  tbody tr:hover{background:#F6F9FE}
  tbody tr.terkunci{background:#FFF8E1 !important}
  tbody td{padding:10px 12px;vertical-align:middle}
  tbody tr.is-new{animation:flashNew 4s forwards}
  @keyframes flashNew{0%,60%{background-color:#DBEAFE}100%{background-color:transparent}}
  .cell-tkt{font-size:11px;font-weight:700;color:var(--blue);font-family:'JetBrains Mono',monospace;white-space:nowrap}
  .cell-time{font-size:11px;color:var(--muted);white-space:nowrap;font-family:'JetBrains Mono',monospace}
  .avatar{width:32px;height:32px;border-radius:50%;background:var(--blue-bg);color:var(--blue);font-size:11px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
  .name-cell{display:flex;align-items:center;gap:8px}
  .name-text{font-size:13px;font-weight:600}
  .chat-id{font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace}
  .msg-cell{max-width:200px;cursor:pointer;padding:6px 8px;border-radius:8px;transition:background .15s}
  .msg-cell:hover{background:#EEF4FF}
  .msg-preview{font-size:12px;color:var(--muted);line-height:1.5;white-space:pre-wrap;word-break:break-word;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
  .msg-hint{font-size:10px;color:#A0AEC0;margin-top:3px}
  .pic-badge{display:inline-block;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;white-space:nowrap}
  .chip{display:inline-flex;align-items:center;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;white-space:nowrap}
  .chip-done{background:var(--green-bg);color:var(--green)}
  .chip-pending{background:var(--orange-bg);color:var(--orange)}
  .chat-input-wrap{display:flex;flex-direction:column;gap:4px;min-width:260px}
  .chat-input-row{display:flex;align-items:center;gap:6px;background:#F0F2F5;border-radius:24px;padding:4px 4px 4px 12px;border:1px solid var(--border)}
  .chat-input-row:focus-within{border-color:var(--tg-blue);background:var(--white)}
  .chat-reply-inp{flex:1;font-size:12.5px;border:none;background:transparent;color:var(--text);outline:none;padding:4px 0;min-width:0;font-family:'Plus Jakarta Sans',sans-serif}
  .chat-reply-inp::placeholder{color:#A0AEC0}
  .chat-pic-sel{font-size:11px;padding:3px 5px;border:1px solid var(--border);border-radius:8px;background:var(--white);color:var(--text);cursor:pointer;outline:none;flex-shrink:0;font-family:'Plus Jakarta Sans',sans-serif}
  .pic-fixed-label{font-size:11px;font-weight:700;padding:3px 10px;border-radius:8px;flex-shrink:0;white-space:nowrap}
  .chat-send-btn{width:34px;height:34px;border-radius:50%;border:none;background:var(--tg-blue);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform 0.2s,opacity 0.2s;opacity:0;pointer-events:none}
  .chat-send-btn.visible{opacity:1;pointer-events:all}
  .chat-send-btn.sending{opacity:0.5;pointer-events:none;cursor:not-allowed}
  .chat-send-btn svg{width:17px;height:17px;fill:#fff}
  .klaim-btn{font-size:11px;font-weight:700;padding:5px 14px;border-radius:20px;border:none;background:var(--tg-blue);color:#fff;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;white-space:nowrap;transition:opacity 0.2s}
  .klaim-btn:hover{opacity:0.85}
  .kunci-info{font-size:11px;font-weight:600;color:#92400E;background:#FEF3C7;border:1px solid #FCD34D;border-radius:8px;padding:5px 10px;display:flex;align-items:center;gap:5px;white-space:nowrap}
  .typing-indicator{font-size:10px;color:var(--tg-blue);font-style:italic;min-height:14px;padding-left:4px}
  .loading-overlay{display:flex;align-items:center;justify-content:center;padding:3rem;flex-direction:column;gap:12px}
  .spinner{width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--navy);border-radius:50%;animation:spin .8s linear infinite}
  @keyframes spin{to{transform:rotate(360deg)}}
  .loading-text{font-size:13px;color:var(--muted)}
  .empty-state{text-align:center;padding:3rem 1rem;color:var(--muted);font-size:13px}
  .modal-overlay{position:fixed;inset:0;background:rgba(10,20,45,.55);backdrop-filter:blur(5px);z-index:300;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;pointer-events:none;transition:opacity .22s ease}
  .modal-overlay.open{opacity:1;pointer-events:all}
  .modal-box{background:var(--white);border-radius:var(--radius-lg);width:100%;max-width:640px;max-height:88vh;display:flex;flex-direction:column;box-shadow:var(--shadow-lg);transform:translateY(14px) scale(.98);transition:transform .22s ease}
  .modal-overlay.open .modal-box{transform:translateY(0) scale(1)}
  .modal-head{padding:1rem 1.25rem .9rem;border-bottom:1px solid var(--border);display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-shrink:0}
  .modal-tiket{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--blue);background:var(--blue-bg);padding:2px 8px;border-radius:4px;display:inline-block;margin-bottom:5px;font-family:'JetBrains Mono',monospace}
  .modal-sender{font-size:14px;font-weight:700}
  .modal-meta{font-size:11px;color:var(--muted);margin-top:3px}
  .modal-close{flex-shrink:0;width:30px;height:30px;border-radius:8px;border:1px solid var(--border);background:transparent;cursor:pointer;font-size:16px;color:var(--muted);display:flex;align-items:center;justify-content:center}
  .modal-body{padding:1rem 1.25rem 1.25rem;overflow-y:auto;flex:1}
  .modal-msg-wrap{background:#F6F8FA;border:1px solid #D0D7DE;border-radius:8px;overflow:hidden}
  .modal-msg-toolbar{background:#EAEEF2;border-bottom:1px solid #D0D7DE;padding:5px 12px;display:flex;align-items:center;justify-content:space-between}
  .modal-msg-label{font-size:11px;color:var(--muted);font-weight:600}
  .modal-copy-btn{font-size:11px;padding:3px 10px;border-radius:5px;border:1px solid #D0D7DE;background:var(--white);color:var(--text);cursor:pointer;display:flex;align-items:center;gap:5px;font-weight:500;font-family:'Plus Jakarta Sans',sans-serif}
  .modal-copy-btn.copied{background:var(--green);color:#fff;border-color:var(--green)}
  .modal-msg{font-size:12.5px;line-height:1.8;white-space:pre-wrap;word-break:break-word;font-family:'JetBrains Mono',monospace;color:var(--text);padding:1rem 1.1rem;max-height:52vh;overflow-y:auto}
  .toast{position:fixed;bottom:24px;right:24px;z-index:999;padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;box-shadow:var(--shadow-md);opacity:0;transform:translateY(8px);transition:all .25s;pointer-events:none}
  .toast.show{opacity:1;transform:translateY(0)}
  .toast.success{background:var(--green);color:#fff}
  .toast.error{background:var(--red);color:#fff}
  #scrollBtn{position:fixed;bottom:70px;right:24px;z-index:200;width:40px;height:40px;border-radius:50%;background:var(--navy);color:#fff;border:none;cursor:pointer;box-shadow:var(--shadow-md);display:none;align-items:center;justify-content:center}
  #scrollBtn.show{display:flex}
  #scrollBtn svg{width:20px;height:20px;fill:#fff}
  .clear-btn{background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#DC2626;border-radius:8px;padding:5px 14px;font-size:12px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;display:none;align-items:center;gap:6px;white-space:nowrap;transition:all 0.2s}
  .clear-btn:hover{background:rgba(239,68,68,0.22);border-color:#DC2626}
  .clear-btn svg{width:14px;height:14px}
  .confirm-overlay{position:fixed;inset:0;background:rgba(10,20,45,.6);backdrop-filter:blur(6px);z-index:400;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;pointer-events:none;transition:opacity .2s}
  .confirm-overlay.open{opacity:1;pointer-events:all}
  .confirm-box{background:#fff;border-radius:16px;padding:2rem;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.2);transform:translateY(12px) scale(.98);transition:transform .2s}
  .confirm-overlay.open .confirm-box{transform:translateY(0) scale(1)}
  .confirm-icon{width:52px;height:52px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem}
  .confirm-icon svg{width:26px;height:26px;color:#DC2626}
  .confirm-title{font-size:17px;font-weight:700;text-align:center;margin-bottom:8px;color:#1A202C}
  .confirm-desc{font-size:13px;color:#6B7280;text-align:center;line-height:1.6;margin-bottom:1.5rem}
  .confirm-input{width:100%;padding:10px 14px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;outline:none;margin-bottom:1rem;transition:border-color .15s}
  .confirm-input:focus{border-color:#DC2626}
  .confirm-actions{display:flex;gap:8px}
  .confirm-cancel{flex:1;padding:10px;border:1px solid #E5E7EB;border-radius:8px;background:transparent;font-size:13px;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#6B7280}
  .confirm-cancel:hover{background:#F9FAFB}
  .confirm-delete{flex:1;padding:10px;border:none;border-radius:8px;background:#DC2626;color:#fff;font-size:13px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;opacity:0.4;pointer-events:none;transition:opacity .2s}
  .confirm-delete.ready{opacity:1;pointer-events:all}
</style>
@endpush

@section('content')

{{-- ===== TOPBAR ===== --}}
<div class="topbar">
  <div class="brand">
    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8l-1.82 8.55c-.13.62-.49.77-.99.48l-2.75-2.03-1.33 1.28c-.15.15-.27.27-.55.27l.19-2.78 5.01-4.52c.22-.19-.05-.3-.33-.11L7.08 14.36l-2.72-.85c-.59-.18-.6-.59.12-.87l10.63-4.1c.49-.18.92.12.73.26z"/></svg>
    Dashboard HD PROVISIONING
  </div>
  <div class="topbar-right">
    <span class="conn-status conn-ok" id="connStatus">Terhubung</span>
    <div class="badge-live"><div class="dot"></div>SEMANGAT!!</div>
    <div class="user-badge">
      @php
        $wi = $warnaPic[$user['name']] ?? ['bg'=>'#E3F2FD','font'=>'#0D47A1'];
        $avatarBg   = $user['role'] === 'ADMIN' ? '#FFD700' : $wi['bg'];
        $avatarColor = $user['role'] === 'ADMIN' ? '#7A5700' : $wi['font'];
        $initials = collect(explode(' ', $user['name']))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
      @endphp
      <div class="user-avatar" style="background:{{ $avatarBg }};color:{{ $avatarColor }}">{{ $initials }}</div>
      <div>
        <div class="user-name">{{ $user['name'] }}</div>
        <div class="user-role">{{ $user['role'] === 'ADMIN' ? 'Administrator' : 'PIC' }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
      @csrf
      <button type="submit" class="logout-btn">Keluar</button>
    </form>
  </div>
</div>

{{-- ===== ROLE BANNER (khusus PIC) ===== --}}
@if($user['role'] === 'PIC')
<div class="role-banner">
  <span>Kamu login sebagai <strong>{{ $user['name'] }}</strong> — nama kamu otomatis dipakai sebagai PIC</span>
</div>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<div class="main">

  {{-- Stats --}}
  <div class="stats">
    <div class="stat"><div class="stat-label">Total</div><div class="stat-val" style="color:var(--navy)" id="s-total">-</div></div>
    <div class="stat"><div class="stat-label">Selesai</div><div class="stat-val" style="color:var(--green)" id="s-done">-</div></div>
    <div class="stat"><div class="stat-label">Menunggu</div><div class="stat-val" style="color:var(--orange)" id="s-wait">-</div></div>
    <div class="stat"><div class="stat-label">PIC Aktif</div><div class="stat-val" style="color:var(--blue)" id="s-pic">-</div></div>
  </div>

  {{-- Toolbar --}}
  <div class="toolbar">
    <div class="search-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input class="search-input" id="searchInput" placeholder="Cari nama, pesan, tiket..." oninput="renderTable()">
    </div>
    <div class="filter-group" id="filterGroup">
      <button class="fbtn active" onclick="setFilter('SEMUA',this)">Semua</button>
      <button class="fbtn" onclick="setFilter('MENUNGGU',this)">Menunggu</button>
      <button class="fbtn" onclick="setFilter('SELESAI',this)">Selesai</button>
    </div>
    @if($user['role'] === 'ADMIN')
    <button class="clear-btn" id="clearDbBtn" onclick="openClearConfirm()" style="display:flex">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
      Hapus DB
    </button>
    @endif
  </div>

  {{-- Table --}}
  <div class="table-card">
    <div class="table-header">
      <div class="table-title">Daftar Laporan Masuk</div>
      <span class="count-badge" id="countBadge">Memuat...</span>
    </div>
    <div class="tbl-wrap">
      <div class="loading-overlay" id="loadingState">
        <div class="spinner"></div>
        <div class="loading-text">Memuat data...</div>
      </div>
      <div class="empty-state" id="emptyState" style="display:none">Tidak ada laporan ditemukan.</div>
      <table id="mainTable" style="display:none">
        <thead>
          <tr>
            <th>Tiket</th><th>Waktu</th><th>Pengirim</th><th>Pesan</th><th>PIC</th><th>Status</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>
</div>

{{-- ===== MODAL PESAN ===== --}}
<div class="modal-overlay" id="msgModal">
  <div class="modal-box">
    <div class="modal-head">
      <div>
        <div class="modal-tiket" id="modalTiket"></div>
        <div class="modal-sender" id="modalSender"></div>
        <div class="modal-meta" id="modalMeta"></div>
      </div>
      <button class="modal-close" id="modalCloseBtn">✕</button>
    </div>
    <div class="modal-body">
      <div class="modal-msg-wrap">
        <div class="modal-msg-toolbar">
          <span class="modal-msg-label">Isi Pesan</span>
          <button class="modal-copy-btn" id="modalCopyBtn"><span id="copyLabel">Salin</span></button>
        </div>
        <div class="modal-msg" id="modalMsg"></div>
      </div>
    </div>
  </div>
</div>

{{-- ===== CONFIRM HAPUS DB ===== --}}
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-box">
    <div class="confirm-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
    </div>
    <div class="confirm-title">Hapus Semua Data?</div>
    <div class="confirm-desc">Semua tiket akan dihapus permanen dan tidak bisa dikembalikan.<br>Ketik <strong>HAPUS</strong> untuk konfirmasi.</div>
    <input class="confirm-input" id="confirmInput" placeholder="Ketik HAPUS di sini..." oninput="checkConfirmInput(this)">
    <div class="confirm-actions">
      <button class="confirm-cancel" onclick="closeClearConfirm()">Batal</button>
      <button class="confirm-delete" id="confirmDeleteBtn" onclick="doClearDatabase()">Hapus Semua</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>
<button id="scrollBtn" onclick="scrollToBottom()">
  <svg viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
</button>

@endsection

@push('scripts')
<script>
const API_BASE     = @json($apiBase);
const CURRENT_USER = @json($user);
const WARNA_PIC    = @json($warnaPic);
const PIC_LIST     = @json($picList);
const PASSWORDS_ADMIN = @json(config('hdpsb.admin_key', 'vanzndt28'));

const POLL_INTERVAL = 2000;

let allData=[], lastIndex=1, activeFilter="SEMUA", pollTimer=null, isPolling=false, newRowIndices=[];
let klaimCache={};

async function api(path, options={}){
  const res = await fetch(API_BASE+path, options);
  let data;
  try{ data = await res.json(); } catch(e){ throw new Error("HTTP "+res.status); }
  if(!res.ok){ throw new Error(data.msg||data.message||"HTTP "+res.status); }
  return data;
}

async function loadData(){
  try{
    const res = await api("/?action=data");
    allData = res.rows; lastIndex = res.lastIndex;
    buildPicFilters(); renderTable(); renderStats();
    document.getElementById("loadingState").style.display = "none";
    document.getElementById("mainTable").style.display   = "table";
    setTimeout(scrollToBottom, 100);
    startPolling(); setConnStatus(true);
  } catch(err){
    document.getElementById("loadingState").style.display = "none";
    setConnStatus(false); showToast("Gagal memuat: "+err.message,"error");
  }
}

function startPolling(){
  if(pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(checkNewRows, POLL_INTERVAL);
}

async function checkNewRows(){
  if(isPolling) return; isPolling=true;
  try{
    const res = await api("/?action=new&lastIndex="+lastIndex);
    setConnStatus(true);
    pollKlaimStatus();

    // Update status tiket yang sudah ada secara realtime
    const resAll = await api("/?action=data");
    resAll.rows.forEach(function(newRow){
      const oldRow = allData.find(r=>String(r._rowIndex)===String(newRow._rowIndex));
      if(oldRow && oldRow.statusKey !== newRow.statusKey){
        oldRow.statusKey = newRow.statusKey;
        oldRow.pic = newRow.pic;
        oldRow.balasan = newRow.balasan;
        updateRow(newRow._rowIndex);
        renderStats();
      }
    });

    if(res.hasNew){
      const wasNearBottom = isNearBottom();
      newRowIndices = res.rows.map(r=>r._rowIndex);
      const savedInputs={};
      document.querySelectorAll(".chat-reply-inp").forEach(function(inp){
        const ri = inp.dataset.rowindex;
        if(inp.value.trim()) savedInputs[ri]=inp.value;
      });
      allData = allData.concat(res.rows); lastIndex = res.lastIndex;
      buildPicFilters(); renderTable(); renderStats();
      Object.keys(savedInputs).forEach(function(ri){
        const inp = document.querySelector('.chat-reply-inp[data-rowindex="'+ri+'"]');
        if(inp){
          inp.value = savedInputs[ri];
          const btn = document.querySelector('.chat-send-btn[data-rowindex="'+ri+'"]');
          if(btn) btn.classList.add("visible");
        }
      });
      if(wasNearBottom) setTimeout(scrollToBottom,80);
      else showToast("💬 "+res.rows.length+" pesan baru","success");
      setTimeout(function(){ newRowIndices=[]; },4000);
    }
  } catch(e){ setConnStatus(false); }
  finally{ isPolling=false; }
}

async function pollKlaimStatus(){
  const allRows = allData.filter(r => r.statusKey !== "done");
  if(allRows.length === 0) return;
  for(const row of allRows){
    const ri = String(row._rowIndex);
    try{
      const res = await fetch(API_BASE+"/?action=klaimStatus&rowIndex="+ri);
      const data = await res.json();
      const now = data.pic || null;
      const prev = klaimCache[ri] || null;
      if(prev !== now){
        if(now){ klaimCache[ri] = now; }
        else { delete klaimCache[ri]; }
        const tr = document.querySelector('tr[data-rowindex="'+ri+'"]');
        if(tr && row.statusKey !== "done"){
          const isAdmin = CURRENT_USER.role === "ADMIN";
          const klaimSaya = now === CURRENT_USER.name;
          const klaimOrang = now && !klaimSaya;
          let html;
          if(klaimOrang){
            html='<div class="chat-input-wrap"><div class="kunci-info">🔒 Sedang dikerjakan <strong>'+escHtml(now)+'</strong></div></div>';
            tr.classList.add("terkunci");
          } else if(klaimSaya){
            html=buatInputBalas(row,isAdmin,true);
            tr.classList.remove("terkunci");
          } else {
            html='<div class="chat-input-wrap"><button class="klaim-btn" data-rowindex="'+ri+'">✋ KERJAKAN</button></div>';
            tr.classList.remove("terkunci");
          }
          tr.cells[6].innerHTML = html;
        }
      }
    } catch(e){}
  }
}

function getFiltered(){
  const q=(document.getElementById("searchInput")||{}).value||"";
  const ql=q.toLowerCase();
  return allData.filter(function(r){
    if(activeFilter==="MENUNGGU"&&r.statusKey==="done") return false;
    if(activeFilter==="SELESAI"&&r.statusKey!=="done") return false;
    if(activeFilter!=="SEMUA"&&activeFilter!=="MENUNGGU"&&activeFilter!=="SELESAI"){ if(r.pic!==activeFilter) return false; }
    if(ql&&!(r.name+r.msg+r.laporanId+r.chatId).toLowerCase().includes(ql)) return false;
    return true;
  });
}

function renderTable(){
  const rows   = getFiltered();
  const tbody  = document.getElementById("tableBody");
  const table  = document.getElementById("mainTable");
  const empty  = document.getElementById("emptyState");
  document.getElementById("countBadge").textContent = rows.length+" laporan";
  const isAdmin = CURRENT_USER.role==="ADMIN";
  if(rows.length===0){ table.style.display="none"; empty.style.display="block"; return; }
  empty.style.display="none"; table.style.display="table";
  tbody.innerHTML=rows.map(function(row){
    const wi       = WARNA_PIC[row.pic]||null;
    const isDone   = row.statusKey==="done";
    const isNew    = newRowIndices.includes(row._rowIndex);
    const klaimOleh = klaimCache[String(row._rowIndex)]||null;
    const saya     = CURRENT_USER.name;
    const klaimSaya  = klaimOleh===saya;
    const klaimOrang = klaimOleh&&!klaimSaya;
    const picBadge = wi?'<span class="pic-badge" style="background:'+wi.bg+';color:'+wi.font+'">'+escHtml(row.pic)+'</span>':'<span style="font-size:11px;color:var(--muted)">-</span>';
    const chip     = isDone?'<span class="chip chip-done">Selesai</span>':'<span class="chip chip-pending">Menunggu</span>';
    let actionCol;
    if(isDone){ actionCol=buatInputBalas(row,isAdmin,false); }
    else if(klaimOrang){ actionCol='<div class="chat-input-wrap"><div class="kunci-info">🔒 Sedang dikerjakan <strong>'+escHtml(klaimOleh)+'</strong></div></div>'; }
    else if(klaimSaya){ actionCol=buatInputBalas(row,isAdmin,true); }
    else { actionCol='<div class="chat-input-wrap"><button class="klaim-btn" data-rowindex="'+row._rowIndex+'">✋ KERJAKAN</button></div>'; }
    const trClass=(isNew?'is-new':'')+(klaimOrang?' terkunci':'');
    return'<tr class="'+trClass.trim()+'" data-rowindex="'+row._rowIndex+'"><td class="cell-tkt">'+(row.laporanId||'-').replace('TKT-','')+'</td><td class="cell-time">'+formatTime(row.timestamp)+'</td><td><div class="name-cell"><div class="avatar">'+getInitials(row.name)+'</div><div><div class="name-text">'+escHtml(row.name)+'</div><div class="chat-id">'+row.chatId+'</div></div></div></td><td><div class="msg-cell" data-rowindex="'+row._rowIndex+'"><div class="msg-preview">'+escHtml(row.msg)+'</div><div class="msg-hint">klik untuk baca lengkap</div></div></td><td>'+picBadge+'</td><td>'+chip+'</td><td>'+actionCol+'</td></tr>';
  }).join("");
}

function buatInputBalas(row,isAdmin,klaimSaya){
  const isDone=row.statusKey==="done";
  let html='<div class="chat-input-wrap">';
  if(isDone){
    html+='<div style="display:flex;flex-direction:column;gap:6px">';
    html+='<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">';
    html+='<span style="font-size:11px;font-weight:700;background:#DCFCE7;color:#166534;padding:3px 10px;border-radius:20px">✅ TERKIRIM</span>';
    if(row.pic) html+='<span style="font-size:11px;font-weight:700;color:var(--muted)">oleh '+escHtml(row.pic)+'</span>';
    html+='<button onclick="toggleEdit('+row._rowIndex+',this)" style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #E5E7EB;background:#F9FAFB;color:#374151;cursor:pointer">Edit</button>';
    html+='</div>';
    html+='<div id="edit-wrap-'+row._rowIndex+'" style="display:none">';
    html+='<div class="chat-input-row"><input class="chat-reply-inp" data-rowindex="'+row._rowIndex+'" placeholder="Kirim ulang balasan..." value="">';
    if(isAdmin){
      html+='<select class="chat-pic-sel" id="ps-'+row._rowIndex+'"><option value="">PIC</option>'+PIC_LIST.map(function(p){ return'<option value="'+p+'"'+(row.pic===p?' selected':'')+'>'+p+'</option>'; }).join('')+'</select>';
    } else {
      const myWi=WARNA_PIC[CURRENT_USER.name]||{bg:"#E3F2FD",font:"#0D47A1"};
      html+='<span class="pic-fixed-label" style="background:'+myWi.bg+';color:'+myWi.font+'">'+escHtml(CURRENT_USER.name)+'</span>';
    }
    html+='<button class="chat-send-btn" data-rowindex="'+row._rowIndex+'"><svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg></button></div>';
    html+='</div></div>';
  } else {
    if(klaimSaya){
      html+='<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2px"><span style="font-size:10px;color:var(--green);font-weight:700">✅ Kamu sedang mengerjakan ini</span><button class="klaim-btn" style="font-size:10px;padding:2px 8px;background:#EF4444" data-lepas="'+row._rowIndex+'">Lepas</button></div>';
    }
    html+='<div class="chat-input-row"><input class="chat-reply-inp" data-rowindex="'+row._rowIndex+'" placeholder="Tulis balasan..." value="">';
    if(isAdmin){
      html+='<select class="chat-pic-sel" id="ps-'+row._rowIndex+'"><option value="">PIC</option>'+PIC_LIST.map(function(p){ return'<option value="'+p+'"'+(row.pic===p?' selected':'')+'>'+p+'</option>'; }).join('')+'</select>';
    } else {
      const myWi=WARNA_PIC[CURRENT_USER.name]||{bg:"#E3F2FD",font:"#0D47A1"};
      html+='<span class="pic-fixed-label" style="background:'+myWi.bg+';color:'+myWi.font+'">'+escHtml(CURRENT_USER.name)+'</span>';
    }
    html+='<button class="chat-send-btn" data-rowindex="'+row._rowIndex+'"><svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg></button></div>';
  }
  html+='<div class="typing-indicator" id="ti-'+row._rowIndex+'"></div></div>';
  return html;
}

function toggleEdit(rowIndex, btn){
  const wrap = document.getElementById("edit-wrap-"+rowIndex);
  if(!wrap) return;
  const isHidden = wrap.style.display === "none";
  wrap.style.display = isHidden ? "block" : "none";
  btn.textContent = isHidden ? "Tutup" : "Edit";
  if(isHidden){ const inp = wrap.querySelector(".chat-reply-inp"); if(inp) inp.focus(); }
}

function setupTableDelegation(){
  const tb=document.getElementById("tableBody");
  tb.addEventListener("click",function(e){
    const msgCell=e.target.closest(".msg-cell");
    if(msgCell){ openModal(parseInt(msgCell.dataset.rowindex)); return; }
    const klaimBtn=e.target.closest(".klaim-btn[data-rowindex]");
    if(klaimBtn){ ambilTiket(parseInt(klaimBtn.dataset.rowindex)); return; }
    const lepasBtn=e.target.closest(".klaim-btn[data-lepas]");
    if(lepasBtn){ lepasKlaim(parseInt(lepasBtn.dataset.lepas)); return; }
    const sendBtn=e.target.closest(".chat-send-btn");
    if(sendBtn&&!sendBtn.disabled&&!sendBtn.classList.contains("sending")) kirimBalasan(parseInt(sendBtn.dataset.rowindex),sendBtn);
  });
  tb.addEventListener("input",function(e){
    if(!e.target.classList.contains("chat-reply-inp")) return;
    const ri=parseInt(e.target.dataset.rowindex);
    const btn=document.querySelector('.chat-send-btn[data-rowindex="'+ri+'"]');
    if(btn) btn.classList.toggle("visible",e.target.value.trim().length>0);
  });
  tb.addEventListener("keydown",function(e){
    if(e.key!=="Enter"||e.shiftKey||!e.target.classList.contains("chat-reply-inp")) return;
    e.preventDefault();
    const ri=parseInt(e.target.dataset.rowindex);
    const btn=document.querySelector('.chat-send-btn[data-rowindex="'+ri+'"]');
    if(btn&&btn.classList.contains("visible")&&!btn.classList.contains("sending")) kirimBalasan(ri,btn);
  });
}

async function ambilTiket(rowIndex){
  const klaimBtn=document.querySelector('.klaim-btn[data-rowindex="'+rowIndex+'"]');
  if(klaimBtn){ klaimBtn.disabled=true; klaimBtn.textContent="⏳ Mengambil..."; klaimBtn.style.opacity="0.6"; }
  try{
    const res  = await fetch(API_BASE+"/?action=klaim&rowIndex="+rowIndex+"&pic="+encodeURIComponent(CURRENT_USER.name));
    const data = await res.json();
    if(data.ok){
      klaimCache[String(rowIndex)]=CURRENT_USER.name;
      const row=allData.find(r=>String(r._rowIndex)===String(rowIndex));
      if(row){
        const tr=document.querySelector('tr[data-rowindex="'+rowIndex+'"]');
        if(tr){ tr.cells[6].innerHTML=buatInputBalas(row,CURRENT_USER.role==="ADMIN",true); tr.classList.remove("terkunci"); const inp=tr.querySelector('.chat-reply-inp'); if(inp) inp.focus(); }
      }
      showToast("Kamu sedang mengerjakan","success");
    } else {
      if(klaimBtn){ klaimBtn.disabled=false; klaimBtn.textContent="✋ KERJAKAN"; klaimBtn.style.opacity=""; }
      showToast("Sedang dikerjakan "+(data.klaimedBy||"orang lain"),"error");
      klaimCache[String(rowIndex)]=data.klaimedBy||"orang lain";
      renderTable();
    }
  } catch(e){
    if(klaimBtn){ klaimBtn.disabled=false; klaimBtn.textContent="✋ KERJAKAN"; klaimBtn.style.opacity=""; }
    showToast("Gagal mengambil tiket","error");
  }
}

async function lepasKlaim(rowIndex){
  try{
    await fetch(API_BASE+"/?action=klaim&rowIndex="+rowIndex+"&pic=clear");
    delete klaimCache[String(rowIndex)];
    renderTable(); showToast("Klaim dilepas","success");
  } catch(e){ showToast("Gagal melepas klaim","error"); }
}

async function kirimBalasan(rowIndex,btn){
  const row=allData.find(r=>r._rowIndex===rowIndex); if(!row) return;
  const inp=document.querySelector('.chat-reply-inp[data-rowindex="'+rowIndex+'"]');
  const balasan=inp?inp.value.trim():"";
  let pic;
  if(CURRENT_USER.role==="PIC"){ pic=CURRENT_USER.name; }
  else { const sel=document.getElementById("ps-"+rowIndex); pic=sel?sel.value:""; }
  if(!balasan){ showToast("Tulis balasan dulu!","error"); return; }
  if(!pic){ showToast("Pilih PIC dulu!","error"); return; }
  btn.classList.remove("visible"); btn.classList.add("sending");
  const prevVal=inp.value; inp.value="";
  try{
    const res=await api("/kirim",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({rowIndex,chatId:row.chatId,balasan,pic,msgId:row.messageId})});
    if(res.ok){
      await fetch(API_BASE+"/?action=klaim&rowIndex="+rowIndex+"&pic=clear");
      delete klaimCache[String(rowIndex)];
      const idx=allData.findIndex(r=>r._rowIndex===rowIndex);
      if(idx>=0){ allData[idx].statusKey="done"; allData[idx].balasan=balasan; allData[idx].pic=pic; }
      updateRow(rowIndex); renderStats(); buildPicFilters();
      showToast("Terkirim ke "+row.name,"success");
    } else {
      inp.value=prevVal; btn.classList.remove("sending"); btn.classList.add("visible");
      showToast("Gagal: "+(res.msg||"Terjadi kesalahan"),"error");
    }
  } catch(err){
    inp.value=prevVal; btn.classList.remove("sending"); btn.classList.add("visible");
    showToast("Error: "+err.message,"error");
  }
}

function updateRow(rowIndex){
  const row=allData.find(r=>r._rowIndex===rowIndex); if(!row) return;
  const tr=document.querySelector('tr[data-rowindex="'+rowIndex+'"]'); if(!tr) return;
  const wi=WARNA_PIC[row.pic]||null;
  tr.cells[4].innerHTML=wi?'<span class="pic-badge" style="background:'+wi.bg+';color:'+wi.font+'">'+escHtml(row.pic)+'</span>':'<span style="font-size:11px;color:var(--muted)">-</span>';
  tr.cells[5].innerHTML='<span class="chip chip-done">Selesai</span>';
  tr.classList.remove("terkunci");
  tr.cells[6].innerHTML=buatInputBalas(row,CURRENT_USER.role==="ADMIN",false);
}

function buildPicFilters(){
  const pics=[...new Set(allData.map(r=>r.pic).filter(Boolean))].sort();
  const fg=document.getElementById("filterGroup");
  fg.querySelectorAll(".fbtn[data-pic]").forEach(function(b){ b.remove(); });
  pics.forEach(function(p){
    const btn=document.createElement("button");
    btn.className="fbtn"; btn.textContent=p; btn.dataset.pic=p;
    btn.onclick=function(){ setFilter(p,btn); };
    fg.appendChild(btn);
  });
}

function setFilter(val,el){
  activeFilter=val;
  document.querySelectorAll(".fbtn").forEach(b=>b.classList.remove("active"));
  el.classList.add("active");
  renderTable(); renderStats();
}

function renderStats(){
  const src=getFiltered();
  const done=src.filter(r=>r.statusKey==="done").length;
  const pics=new Set(src.filter(r=>r.pic).map(r=>r.pic)).size;
  setStatVal("s-total",allData.length); setStatVal("s-done",done);
  setStatVal("s-wait",src.length-done); setStatVal("s-pic",pics);
}

function setStatVal(id,val){ document.getElementById(id).textContent=val; }

function setupModal(){
  document.getElementById("msgModal").addEventListener("click",function(e){ if(e.target===this) closeModal(); });
  document.getElementById("modalCloseBtn").addEventListener("click",closeModal);
  document.getElementById("modalCopyBtn").addEventListener("click",copyModalMsg);
  document.addEventListener("keydown",function(e){ if(e.key==="Escape") closeModal(); });
}

function openModal(rowIndex){
  const row=allData.find(r=>r._rowIndex===rowIndex); if(!row) return;
  document.getElementById("modalTiket").textContent=(row.laporanId||"ID "+row.chatId).replace("TKT-","");
  document.getElementById("modalSender").textContent=row.name||"-";
  document.getElementById("modalMeta").textContent=formatTime(row.timestamp)+(row.chatId?"  "+row.chatId:"");
  document.getElementById("modalMsg").textContent=row.msg||"";
  document.getElementById("copyLabel").textContent="Salin";
  document.getElementById("modalCopyBtn").classList.remove("copied");
  document.getElementById("msgModal").classList.add("open");
  document.body.style.overflow="hidden";
}

function closeModal(){ document.getElementById("msgModal").classList.remove("open"); document.body.style.overflow=""; }

function copyModalMsg(){
  navigator.clipboard.writeText(document.getElementById("modalMsg").textContent).then(function(){
    document.getElementById("copyLabel").textContent="Tersalin!";
    document.getElementById("modalCopyBtn").classList.add("copied");
    setTimeout(function(){ document.getElementById("copyLabel").textContent="Salin"; document.getElementById("modalCopyBtn").classList.remove("copied"); },2000);
  }).catch(function(){ showToast("Gagal menyalin","error"); });
}

function openClearConfirm(){
  document.getElementById("confirmInput").value="";
  document.getElementById("confirmDeleteBtn").classList.remove("ready");
  document.getElementById("confirmOverlay").classList.add("open");
  setTimeout(function(){ document.getElementById("confirmInput").focus(); },200);
}

function closeClearConfirm(){ document.getElementById("confirmOverlay").classList.remove("open"); }

function checkConfirmInput(inp){
  document.getElementById("confirmDeleteBtn").classList.toggle("ready",inp.value.trim()==="HAPUS");
}

async function doClearDatabase(){
  const btn=document.getElementById("confirmDeleteBtn");
  if(!btn.classList.contains("ready")) return;
  btn.textContent="Menghapus..."; btn.style.opacity="0.6"; btn.style.pointerEvents="none";
  try{
    const res=await fetch(API_BASE+"/?action=clearDb&adminKey="+encodeURIComponent(PASSWORDS_ADMIN));
    const data=await res.json();
    if(data.ok){
      closeClearConfirm(); allData=[]; lastIndex=1; klaimCache={};
      renderTable(); renderStats(); buildPicFilters();
      showToast("Database berhasil dikosongkan","success");
    } else { showToast("Gagal: "+(data.msg||"Error"),"error"); }
  } catch(e){ showToast("Error: "+e.message,"error"); }
  finally{ btn.textContent="Hapus Semua"; btn.style.opacity=""; btn.style.pointerEvents=""; }
}

function setConnStatus(ok){
  const el=document.getElementById("connStatus");
  el.textContent=ok?"Terhubung":"Terputus";
  el.className="conn-status "+(ok?"conn-ok":"conn-err");
}
function isNearBottom(){ return(window.innerHeight+window.scrollY)>=document.body.scrollHeight-120; }
function scrollToBottom(){ window.scrollTo({top:document.body.scrollHeight,behavior:"smooth"}); }
window.addEventListener("scroll",function(){
  const btn=document.getElementById("scrollBtn");
  if(btn) btn.classList.toggle("show",!isNearBottom()&&allData.length>0);
},{passive:true});
function getInitials(name){ return(name||"?").split(" ").slice(0,2).map(n=>n[0]).join("").toUpperCase(); }
function formatTime(ts){
  if(!ts) return"-";
  try{ const d=new Date(ts); return d.toLocaleDateString("id-ID",{day:"2-digit",month:"short"})+" "+d.toLocaleTimeString("id-ID",{hour:"2-digit",minute:"2-digit"}); }
  catch(e){ return ts; }
}
function escHtml(s){ return String(s||"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;"); }
let toastTimer;
function showToast(msg,type){
  const t=document.getElementById("toast");
  t.textContent=msg; t.className="toast "+type+" show";
  clearTimeout(toastTimer);
  toastTimer=setTimeout(function(){ t.classList.remove("show"); },3200);
}

window.addEventListener("DOMContentLoaded",function(){
  setupModal(); setupTableDelegation(); loadData();
});
</script>
@endpush
'@ | Set-Content resources\views\dashboard\index.blade.php -Encoding UTF8