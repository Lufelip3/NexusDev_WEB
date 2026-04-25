<?php
ob_start();
include_once("../Objetos/drogariaController.php");

$controller = new drogariaController();
$drog = null;

if (isset($_GET["id"])) {
    $drog = $controller->localizarDrogaria($_GET["id"]);
}

if (!$drog) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Detalhes da drogaria – PharmaPulse ERP">
  <title>Visualizar Drogaria – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    /* Estilos específicos para a visualização premium */
    .ph-view-wrapper {
      padding: 32px 16px;
      max-width: 900px;
      margin: 0 auto;
      animation: fadeIn 0.5s ease-out forwards;
    }
    .ph-view-card {
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.06);
      border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .ph-view-header {
      background: linear-gradient(135deg, #102c26 0%, #1a3d35 100%);
      padding: 40px;
      display: flex;
      align-items: center;
      gap: 32px;
      color: white;
    }
    .ph-view-icon-wrap {
      width: 120px;
      height: 120px;
      border-radius: 24px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }
    .ph-view-icon-placeholder {
      font-size: 3.5rem;
    }
    .ph-view-title-area h2 {
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 8px;
      color: white;
      font-family: 'Inter', sans-serif;
    }
    .ph-view-title-area h2::before { display: none; }
    .ph-view-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(74, 222, 128, 0.2);
      color: #4ade80;
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .ph-view-body {
      padding: 40px;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 32px;
    }
    .ph-info-item {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .ph-info-label {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #94a3b8;
    }
    .ph-info-value {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      font-family: 'Inter', sans-serif;
    }
    .ph-view-footer {
      background: #f8fafc;
      padding: 24px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #e2e8f0;
    }
    .ph-btn-back {
      color: #64748b;
      text-decoration: none;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .ph-btn-back:hover { color: #1e293b; }
    .ph-view-actions {
      display: flex;
      gap: 12px;
    }
    .ph-action-link {
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.2s;
    }
    .ph-action-edit {
      background: #3b82f6;
      color: white;
    }
    .ph-action-edit:hover { background: #2563eb; transform: translateY(-1px); }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 640px) {
      .ph-view-header { flex-direction: column; text-align: center; gap: 20px; }
      .ph-view-body { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body class="pharma-app">

  <!-- ═══ SIDEBAR ══════════════════════════════ -->
  <aside class="ph-sidebar" id="ph-sidebar">
    <div class="ph-sidebar-brand">
      <img src="../cfa_logo.png" alt="Logo CFA" class="ph-brand-logo">
      <span class="ph-brand-name">Distribuidora<br>CFA Ltda.</span>
    </div>
    <nav class="ph-sidebar-nav">
      <a href="../Medicamento/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">💊</span>
        <span class="ph-nav-label">Medicamentos</span>
      </a>
      <a href="../index.php" class="ph-nav-item">
        <span class="ph-nav-icon">👥</span>
        <span class="ph-nav-label">Funcionários</span>
      </a>
      <a href="../laboratorio/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🔬</span>
        <span class="ph-nav-label">Laboratórios</span>
      </a>
      <a href="index.php" class="ph-nav-item ph-nav-active">
        <span class="ph-nav-icon">🏪</span>
        <span class="ph-nav-label">Drogarias</span>
      </a>
      <a href="../Compra/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🛒</span>
        <span class="ph-nav-label">Compras</span>
      </a>
      <a href="../Venda/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">📈</span>
        <span class="ph-nav-label">Vendas</span>
      </a>
    </nav>
    <div class="ph-sidebar-footer">
      <a href="../index.php" class="ph-btn-exit"><span>⏻</span> Sair</a>
    </div>
  </aside>

  <!-- ═══ MAIN ══════════════════════════════════ -->
  <div class="ph-main" id="ph-main">
    <header class="ph-topbar">
      <div class="ph-topbar-left">
        <button class="ph-hamburger" id="ph-hamburger" aria-label="Abrir menu">
          <span></span><span></span><span></span>
        </button>
        <div class="ph-topbar-titles">
          <h1 class="ph-page-title">Ficha da Drogaria</h1>
          <p class="ph-page-subtitle">Detalhes cadastrais e localização da unidade</p>
        </div>
      </div>
      <div class="ph-topbar-right">
        <div class="ph-avatar">A</div>
      </div>
    </header>

    <div class="ph-content">
      <div class="ph-view-wrapper">
        <div class="ph-view-card">
          
          <div class="ph-view-header">
            <div class="ph-view-icon-wrap">
              <span class="ph-view-icon-placeholder">🏪</span>
            </div>
            <div class="ph-view-title-area">
              <span class="ph-view-status">● Unidade Ativa</span>
              <h2><?= htmlspecialchars($drog['Nome_Drog'] ?? '') ?></h2>
              <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Registrada no sistema PharmaPulse ERP</p>
            </div>
          </div>

          <div class="ph-view-body">
            <div class="ph-info-item">
              <span class="ph-info-label">Nome da Unidade</span>
              <span class="ph-info-value"><?= htmlspecialchars($drog['Nome_Drog'] ?? '') ?></span>
            </div>
            <div class="ph-info-item">
              <span class="ph-info-label">CNPJ</span>
              <span class="ph-info-value"><?= htmlspecialchars($drog['CNPJ_Drog'] ?? '') ?></span>
            </div>
            <div class="ph-info-item">
              <span class="ph-info-label">E-mail Comercial</span>
              <span class="ph-info-value"><?= htmlspecialchars($drog['Email_Drog'] ?? '') ?></span>
            </div>
            <div class="ph-info-item">
              <span class="ph-info-label">Telefone de Contato</span>
              <span class="ph-info-value"><?= htmlspecialchars($drog['Telefone_Drog'] ?? '') ?></span>
            </div>
            <div class="ph-info-item">
              <span class="ph-info-label">CEP</span>
              <span class="ph-info-value"><?= htmlspecialchars($drog['Cep_Drog'] ?? '') ?></span>
            </div>
            <div class="ph-info-item">
              <span class="ph-info-label">Número</span>
              <span class="ph-info-value"><?= htmlspecialchars($drog['Num_Drog'] ?? '') ?></span>
            </div>
          </div>

          <div class="ph-view-footer">
            <a href="index.php" class="ph-btn-back"><span>←</span> Voltar para a lista</a>
            <div class="ph-view-actions">
              <a href="atualizar.php?alterar=<?= $drog['CNPJ_Drog'] ?>" class="ph-action-link ph-action-edit">Editar Drogaria</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- ═══ BOTTOM NAV (mobile) ══════════════════ -->
  <nav class="ph-bottom-nav">
    <a href="../index.php" class="ph-bottom-item">
      <span class="ph-bottom-icon">🏠</span>
      <span class="ph-bottom-label">Home</span>
    </a>
    <a href="index.php" class="ph-bottom-item ph-bottom-active">
      <span class="ph-bottom-icon">🏪</span>
      <span class="ph-bottom-label">Drogarias</span>
    </a>
    <a href="#" class="ph-bottom-item">
      <span class="ph-bottom-icon">📷</span>
      <span class="ph-bottom-label">Scan</span>
    </a>
  </nav>

  <script>
    const hamburger = document.getElementById('ph-hamburger');
    const sidebar   = document.getElementById('ph-sidebar');
    hamburger.addEventListener('click', () => { sidebar.classList.toggle('ph-sidebar--open'); });
  </script>
</body>
</html>
<?php ob_end_flush(); ?>
