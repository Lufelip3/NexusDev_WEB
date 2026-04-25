<!DOCTYPE html><?php
ob_start();
include_once("../Objetos/drogariaController.php");

$controller = new DrogariaController();
$drogaria   = $controller->index();
global $drogaria;
$a = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pesquisar"])) {
        $a = $controller->pesquisarDrogaria($_POST["pesquisar"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["excluir"])) {
        $a = $controller->excluirDrogaria($_GET["excluir"]);
    }
}

$totalDrogs = $drogaria ? count($drogaria) : 0;
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Gerenciamento de drogarias – PharmaPulse ERP">
  <title>Drogarias – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
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
      <a href="../index.php" class="ph-btn-exit">
        <span>⏻</span> Sair
      </a>
    </div>

  </aside>

  <!-- ═══ MAIN ══════════════════════════════════ -->
  <div class="ph-main" id="ph-main">

    <!-- Top Bar -->
    <header class="ph-topbar">
      <div class="ph-topbar-left">
        <button class="ph-hamburger" id="ph-hamburger" aria-label="Abrir menu">
          <span></span><span></span><span></span>
        </button>
        <div class="ph-topbar-titles">
          <h1 class="ph-page-title">Drogarias</h1>
          <p class="ph-page-subtitle">Controle e rastreio de unidades farmacêuticas</p>
        </div>
      </div>
      <div class="ph-topbar-right">
        <div class="ph-avatar" title="Perfil do usuário">A</div>
      </div>
    </header>

    <!-- Scrollable content -->
    <div class="ph-content">

      <!-- ── Busca ───────────────────────────── -->
      <section class="ph-search-section" aria-label="Pesquisar drogaria">
        <form method="POST" action="index.php" class="ph-search-form">
          <div class="ph-search-input-wrap">
            <span class="ph-search-icon">🔍</span>
            <input
              type="text"
              name="pesquisar"
              class="ph-search-input"
              placeholder="Digite o CNPJ para buscar..."
              value=""
            >
          </div>
          <button type="submit" class="ph-filter-btn">
            <span>⚙</span> Filtrar
          </button>
        </form>
      </section>

      <!-- ── Resultado da pesquisa ──────────── -->
      <?php if ($a && is_array($a)) : ?>
      <section class="ph-search-result" aria-label="Resultado da busca">
        <p class="ph-result-label">Resultado da busca</p>
        <div class="ph-lab-list">
          <div class="ph-lab-card">
            <div class="ph-lab-icon-wrap" style="background:#e8f0fe; display:flex; align-items:center; justify-content:center;">
              <span class="ph-lab-icon">🏪</span>
            </div>
            <div class="ph-lab-info">
              <span class="ph-lab-name"><?= htmlspecialchars($a['Nome_Drog'] ?? '') ?></span>
              <span class="ph-lab-cnpj">CNPJ: <?= htmlspecialchars($a['CNPJ_Drog'] ?? '') ?></span>
            </div>
            <div class="ph-lab-actions-col">
              <span class="ph-badge ph-badge--active">ENCONTRADO</span>
              <div class="ph-action-btns">
                <a href="atualizar.php?alterar=<?= $a['CNPJ_Drog'] ?>"
                   class="ph-btn ph-btn--edit"
                   title="Alterar">✏</a>
                <a href="visualizar.php?id=<?= $a['CNPJ_Drog'] ?>"
                   class="ph-btn ph-btn--view"
                   title="Visualizar">👁</a>
                <a href="index.php?excluir=<?= $a['CNPJ_Drog'] ?>"
                   class="ph-btn ph-btn--delete"
                   title="Excluir"
                   onclick="return confirm('Deseja excluir esta drogaria?')">🗑</a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php endif; ?>

      <!-- ── KPI Cards ──────────────────────── -->
      <section class="ph-kpi-section" aria-label="Indicadores">
        <div class="ph-kpi-card ph-kpi-card--primary">
          <div class="ph-kpi-left">
            <span class="ph-kpi-value"><?= $totalDrogs ?></span>
            <span class="ph-kpi-label">Drogarias Ativas</span>
          </div>
          <div class="ph-kpi-icon">🏪</div>
        </div>
      </section>

      <!-- ── Lista de Drogarias ─────────────── -->
      <section class="ph-list-section" aria-label="Unidades registradas">

        <div class="ph-list-header">
          <h2 class="ph-section-title">Unidades Registradas</h2>
          <div style="display: flex; gap: 12px; align-items: center;">
            <a href="excluidos.php" class="ph-action-btn-secondary">
              <span style="font-size: 1rem; line-height: 1;">🗑</span> Ver Excluídos
            </a>
            <a href="cadastro.php" class="ph-action-btn-primary">
              <span style="font-size: 1.1rem; line-height: 1; margin-right: -2px;">+</span> Nova Drogaria
            </a>
          </div>
        </div>

        <div class="ph-lab-list" id="ph-drog-list">

          <?php if ($drogaria) : ?>
            <?php
              $icon_bgs = ['#e8f0fe','#e8f5e9','#fff3e0','#fce4ec','#e3f2fd','#f3e5f5'];
              $icons    = ['🏪','💊','🏥','🧴','💉','🩺'];
              $i = 0;
              foreach ($drogaria as $drog) :
                $bg   = $icon_bgs[$i % count($icon_bgs)];
                $icon = $icons[$i % count($icons)];
                $i++;
            ?>
            <div class="ph-lab-card">

              <div class="ph-lab-icon-wrap" style="background:<?= $bg ?>; display:flex; align-items:center; justify-content:center;">
                <span class="ph-lab-icon"><?= $icon ?></span>
              </div>

              <div class="ph-lab-info">
                <span class="ph-lab-name"><?= htmlspecialchars($drog->Nome_Drog) ?></span>
                <span class="ph-lab-cnpj">CNPJ: <?= htmlspecialchars($drog->CNPJ_Drog) ?></span>
                <span class="ph-lab-meta">
                  <?= htmlspecialchars($drog->Email_Drog) ?>
                  &nbsp;·&nbsp;
                  <?= htmlspecialchars($drog->Telefone_Drog) ?>
                </span>
              </div>

              <div class="ph-lab-actions-col">
                <span class="ph-badge ph-badge--active">ATIVA</span>
                <div class="ph-action-btns">
                  <a href="atualizar.php?alterar=<?= $drog->CNPJ_Drog ?>"
                     class="ph-btn ph-btn--edit"
                     title="Alterar">✏</a>
                  <a href="visualizar.php?id=<?= $drog->CNPJ_Drog ?>"
                     class="ph-btn ph-btn--view"
                     title="Visualizar">👁</a>
                  <a href="index.php?excluir=<?= $drog->CNPJ_Drog ?>"
                     class="ph-btn ph-btn--delete"
                     title="Excluir"
                     onclick="return confirm('Deseja excluir esta drogaria?')">🗑</a>
                </div>
              </div>

            </div>
            <?php endforeach; ?>

          <?php else : ?>
            <div class="ph-empty-state">
              <span class="ph-empty-icon">🏪</span>
              <p>Nenhuma drogaria cadastrada ainda.</p>
            </div>
          <?php endif; ?>

        </div><!-- .ph-lab-list -->

      </section>

    </div><!-- .ph-content -->

    <footer class="ph-footer">
      <p>Desenvolvido por <strong>NexusDev</strong> &copy; 2026</p>
    </footer>

  </div><!-- .ph-main -->

  <!-- ═══ FAB ═══════════════════════════════════ -->
  <a href="cadastro.php" class="ph-fab" id="ph-fab" title="Nova Drogaria">+</a>

  <!-- ═══ BOTTOM NAV (mobile) ══════════════════ -->
  <nav class="ph-bottom-nav" aria-label="Navegação principal">
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
    <a href="#" class="ph-bottom-item">
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