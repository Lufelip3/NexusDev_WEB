<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

// Pega os dados do usuário logado diretamente da sessão (já preenchida no login)
$_sidebar_nome  = $_SESSION['login']->Nome_Fun ?? ($_SESSION['nome'] ?? 'Usuário');
$_sidebar_cargo    = $_SESSION['login']->Funcao   ?? 'Colaborador';
$_sidebar_is_admin = ($_sidebar_cargo === 'Administrador');

// Gera as iniciais (primeiro + último nome)
$_sidebar_parts    = array_filter(explode(' ', trim($_sidebar_nome)));
$_sidebar_initials = count($_sidebar_parts) >= 2
    ? mb_strtoupper(mb_substr(reset($_sidebar_parts), 0, 1) . mb_substr(end($_sidebar_parts), 0, 1))
    : mb_strtoupper(mb_substr($_sidebar_nome, 0, 2));
?>
<!-- ── Usuário Logado ── -->
<div style="display:flex;align-items:center;gap:10px;padding:10px 12px;margin-bottom:4px;border-radius:10px;background:rgba(255,255,255,0.07);">
  <div style="flex-shrink:0;width:42px;height:42px;border-radius:50%;background:#2a315e;border:2px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.95rem;font-weight:700;color:#fff;letter-spacing:0.5px;"><?= htmlspecialchars($_sidebar_initials) ?></div>
  <div style="display:flex;flex-direction:column;min-width:0;">
    <span style="font-size:0.85rem;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($_sidebar_nome) ?></span>
    <span style="font-size:0.72rem;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.04em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($_sidebar_cargo) ?></span>
  </div>
</div>
<hr style="border-color:rgba(255,255,255,0.12);margin:10px 4px 12px;opacity:1;">

<style>
  .b5-sidebar .nav-link { font-size: 16px !important; }
</style>

