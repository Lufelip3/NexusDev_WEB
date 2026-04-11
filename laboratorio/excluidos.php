<!DOCTYPE html><?php
ob_start();
include_once ("../objetos/laboratorioController.php");

$controller = new laboratorioController();
$excluidos  = $controller->excluidos();
global $excluidos;

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["reativar"])) {
    $controller->reativarLaboratorio($_GET["reativar"]);
}
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Laboratórios desativados – PharmaPulse ERP">
  <title>Laboratórios Excluídos – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .ph-deleted-section {
      padding: 24px 16px;
      animation: fadeIn 0.5s ease-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    /* Reuse card styles from lab list */
    .ph-lab-card {
      background: #fff;
      border-radius: 16px;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      gap: 20px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 12px rgba(0,0,0,0.04);
      transition: transform 0.2s, box-shadow 0.2s;
      opacity: 0.82;
    }
    .ph-lab-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.09);
      opacity: 1;
    }
    .ph-lab-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }
    .ph-lab-icon-wrap {
      width: 56px; height: 56px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem; flex-shrink: 0;
      background: #fef2f2;
    }
    .ph-lab-info { flex: 1; min-width: 0; }
    .ph-lab-name {
      display: block; font-weight: 700; font-size: 0.95rem;
      color: #1e293b; margin-bottom: 4px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .ph-lab-cnpj { display: block; font-size: 0.8rem; color: #94a3b8; }
    .ph-lab-meta { display: block; font-size: 0.78rem; color: #94a3b8; margin-top: 2px; }
    .ph-lab-actions-col {
      display: flex; flex-direction: column;
      align-items: flex-end; gap: 8px; flex-shrink: 0;
    }
    .ph-badge-deleted {
      display: inline-flex; align-items: center; gap: 5px;
      background: #fef2f2; color: #ef4444;
      padding: 4px 10px; border-radius: 50px;
      font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .ph-btn-reactivate {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: 10px;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: #fff; font-weight: 600; font-size: 0.82rem;
      text-decoration: none;
      transition: all 0.2s;
    }
    .ph-btn-reactivate:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    }
    .ph-list-header {
      display: flex; justify-content: space-between;
      align-items: center; margin-bottom: 20px;
    }
    .ph-section-title {
      font-size: 1.25rem; font-weight: 700; color: #1e293b;
    }
    .ph-kpi-section { margin-bottom: 24px; }
    .ph-kpi-card { 
      background: #fff; border-radius: 16px; padding: 20px 24px;
      display: flex; align-items: center; gap: 16px;
      border: 1px solid #e2e8f0; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
      max-width: 220px;
    }
    .ph-kpi-left { display: flex; flex-direction: column; }
    .ph-kpi-value { font-size: 2rem; font-weight: 800; color: #ef4444; }
    .ph-kpi-label { font-size: 0.8rem; color: #64748b; font-weight: 600; }
    .ph-kpi-icon { font-size: 2rem; margin-left: auto; }
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
      <a href="index.php" class="ph-nav-item ph-nav-active">
        <span class="ph-nav-icon">🔬</span>
        <span class="ph-nav-label">Laboratórios</span>
      </a>
      <a href="#" class="ph-nav-item">
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
          <h1 class="ph-page-title">Laboratórios Desativados</h1>
          <p class="ph-page-subtitle">Unidades removidas do sistema – possível reativação</p>
        </div>
      </div>
      <div class="ph-topbar-right">
        <div class="ph-avatar" title="Perfil do usuário">A</div>
      </div>
    </header>

    <div class="ph-content">
      <div class="ph-deleted-section">

        <!-- KPI -->
        <section class="ph-kpi-section" aria-label="Total desativadas">
          <div class="ph-kpi-card">
            <div class="ph-kpi-left">
              <span class="ph-kpi-value"><?= $excluidos ? count($excluidos) : 0 ?></span>
              <span class="ph-kpi-label">Desativadas</span>
            </div>
            <div class="ph-kpi-icon">🗑</div>
          </div>
        </section>

        <!-- Header da lista -->
        <div class="ph-list-header">
          <h2 class="ph-section-title">Unidades Desativadas</h2>
          <a href="index.php" class="ph-action-btn-primary" id="link-voltar">
            ← Voltar ao Painel
          </a>
        </div>

        <!-- Cards -->
        <div class="ph-lab-list" id="ph-excluidos-list">

          <?php if ($excluidos) : ?>
            <?php foreach($excluidos as $lab) : ?>
            <div class="ph-lab-card">

              <div class="ph-lab-icon-wrap">
                <?php if (!empty($lab->Foto_Lab)) : ?>
                  <img src="../uploads/laboratorios/<?= htmlspecialchars($lab->Foto_Lab) ?>"
                       alt="<?= htmlspecialchars($lab->Nome_Lab) ?>"
                       style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                <?php else : ?>
                  🚫
                <?php endif; ?>
              </div>

              <div class="ph-lab-info">
                <span class="ph-lab-name"><?= htmlspecialchars($lab->Nome_Lab) ?></span>
                <span class="ph-lab-cnpj">CNPJ: <?= htmlspecialchars($lab->CNPJ_Lab) ?></span>
                <span class="ph-lab-meta">
                  <?= htmlspecialchars($lab->Email_Lab) ?>
                  &nbsp;·&nbsp;
                  <?= htmlspecialchars($lab->Telefone_Lab) ?>
                </span>
              </div>

              <div class="ph-lab-actions-col">
                <span class="ph-badge-deleted">● Desativada</span>
                <a href="excluidos.php?reativar=<?= urlencode($lab->CNPJ_Lab) ?>"
                   class="ph-btn-reactivate"
                   onclick="return confirm('Deseja reativar o laboratório <?= htmlspecialchars(addslashes($lab->Nome_Lab)) ?>?')">
                  ♻ Reativar
                </a>
              </div>

            </div>
            <?php endforeach; ?>

          <?php else : ?>
            <div class="ph-empty-state">
              <span class="ph-empty-icon">✅</span>
              <p>Nenhum laboratório desativado. Tudo limpo!</p>
            </div>
          <?php endif; ?>

        </div><!-- .ph-lab-list -->

      </div>
    </div><!-- .ph-content -->

    <footer class="ph-footer">
      <p>Desenvolvido por <strong>NexusDev</strong> &copy; 2026</p>
    </footer>
  </div><!-- .ph-main -->

  <!-- Bottom Nav (mobile) -->
  <nav class="ph-bottom-nav" aria-label="Navegação principal">
    <a href="../index.php" class="ph-bottom-item" id="bn-home">
      <span class="ph-bottom-icon">🏠</span>
      <span class="ph-bottom-label">Home</span>
    </a>
    <a href="index.php" class="ph-bottom-item ph-bottom-active" id="bn-labs">
      <span class="ph-bottom-icon">🔬</span>
      <span class="ph-bottom-label">Labs</span>
    </a>
    <a href="#" class="ph-bottom-item" id="bn-scan">
      <span class="ph-bottom-icon">📷</span>
      <span class="ph-bottom-label">Scan</span>
    </a>
    <a href="#" class="ph-bottom-item" id="bn-reports">
      <span class="ph-bottom-icon">📊</span>
      <span class="ph-bottom-label">Reports</span>
    </a>
  </nav>

  <script>
    const hamburger = document.getElementById('ph-hamburger');
    const sidebar   = document.getElementById('ph-sidebar');

    hamburger.addEventListener('click', () => {
      sidebar.classList.toggle('ph-sidebar--open');
    });

    document.addEventListener('click', (e) => {
      if (
        sidebar.classList.contains('ph-sidebar--open') &&
        !sidebar.contains(e.target) &&
        !hamburger.contains(e.target)
      ) {
        sidebar.classList.remove('ph-sidebar--open');
      }
    });
  </script>

</body>
</html>
<?php ob_end_flush(); ?>