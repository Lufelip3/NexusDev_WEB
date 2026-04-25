<!DOCTYPE html><?php
ob_start();
include_once("../Objetos/laboratorioController.php");

$controller  = new LaboratorioController();
$laboratorio = $controller->index();
global $laboratorio;
$a = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pesquisar"]) && isset($_POST["tipo_busca"])) {
        $a = $controller->pesquisarLaboratorio($_POST["tipo_busca"], $_POST["pesquisar"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["excluir"])) {
        $a = $controller->excluirLaboratorio($_GET["excluir"]);
    }
}

$totalLabs = $laboratorio ? count($laboratorio) : 0;
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Gerenciamento de laboratórios – PharmaPulse ERP">
  <title>Laboratórios – PharmaPulse</title>
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
      <a href="index.php" class="ph-nav-item ph-nav-active">
        <span class="ph-nav-icon">🔬</span>
        <span class="ph-nav-label">Laboratórios</span>
      </a>
      <a href="../drogaria/index.php" class="ph-nav-item">
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
          <h1 class="ph-page-title">Laboratórios</h1>
          <p class="ph-page-subtitle">Controle e rastreio de unidades clínicas</p>
        </div>
      </div>
      <div class="ph-topbar-right">
        <div class="ph-avatar" title="Perfil do usuário">A</div>
      </div>
    </header>

    <!-- Scrollable content -->
    <div class="ph-content">

      <!-- ── Busca ───────────────────────────── -->
      <section class="ph-search-section" aria-label="Pesquisar laboratório">
        <form method="POST" action="index.php" class="ph-search-form">
          <select name="tipo_busca" class="ph-select">
            <option value="nome" <?= (isset($_POST['tipo_busca']) && $_POST['tipo_busca'] == 'nome') ? 'selected' : '' ?>>Nome</option>
            <option value="cnpj" <?= (isset($_POST['tipo_busca']) && $_POST['tipo_busca'] == 'cnpj') ? 'selected' : '' ?>>CNPJ</option>
          </select>
          <div class="ph-search-input-wrap">
            <span class="ph-search-icon">🔍</span>
            <input
              type="text"
              id="pesquisar"
              name="pesquisar"
              class="ph-search-input"
              placeholder="Digite o termo buscando..."
              value=""
            >
          </div>
          <button type="submit" class="ph-filter-btn" id="btn-filtrar">
            <span>⚙</span> Filtrar
          </button>
        </form>
      </section>

      <!-- ── Resultado da pesquisa ──────────── -->
      <?php if ($a && is_array($a)) : ?>
      <section class="ph-search-result" aria-label="Resultado da busca">
        <p class="ph-result-label">Resultado da busca</p>
        <div class="ph-lab-list">
          <?php foreach($a as $res): ?>
          <div class="ph-lab-card">
            <div class="ph-lab-icon-wrap" style="background:#e8f0fe; overflow: hidden; display: flex; align-items: center; justify-content: center;">
              <?php if (!empty($res->Foto_Lab)) : ?>
                <img src="../uploads/laboratorios/<?= htmlspecialchars($res->Foto_Lab) ?>" 
                     alt="<?= htmlspecialchars($res->Nome_Lab) ?>"
                     style="width: 100%; height: 100%; object-fit: cover;">
              <?php else : ?>
                <span class="ph-lab-icon">🔍</span>
              <?php endif; ?>
            </div>
            <div class="ph-lab-info">
              <span class="ph-lab-name"><?= htmlspecialchars($res->Nome_Lab ?? '') ?></span>
              <span class="ph-lab-cnpj">CNPJ: <?= htmlspecialchars($res->CNPJ_Lab ?? '') ?></span>
            </div>
            <div class="ph-lab-actions-col">
              <span class="ph-badge ph-badge--active">ENCONTRADO</span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- ── KPI Cards ──────────────────────── -->
      <section class="ph-kpi-section" aria-label="Indicadores">

        <div class="ph-kpi-card ph-kpi-card--primary">
          <div class="ph-kpi-left">
            <span class="ph-kpi-value"><?= $totalLabs ?></span>
            <span class="ph-kpi-label">Labs Ativos</span>
          </div>
          <div class="ph-kpi-icon">🔬</div>
        </div>

      </section>

      <!-- ── Lista de Laboratórios ──────────── -->
      <section class="ph-list-section" aria-label="Unidades registradas">

        <div class="ph-list-header">
          <h2 class="ph-section-title">Unidades Registradas</h2>
          <div style="display: flex; gap: 12px; align-items: center;">
            <a href="excluidos.php" class="ph-action-btn-secondary">
              <span style="font-size: 1rem; line-height: 1;">🗑</span> Ver Excluídos
            </a>
            <a href="cadastro.php" class="ph-action-btn-primary" id="link-novo-lab">
              <span style="font-size: 1.1rem; line-height: 1; margin-right: -2px;">+</span> Novo Lab
            </a>
          </div>
        </div>

        <div class="ph-lab-list" id="ph-lab-list">

          <?php if ($laboratorio) : ?>
            <?php
              $icon_bgs = ['#e8f0fe','#e8f5e9','#fff3e0','#fce4ec','#e3f2fd','#f3e5f5'];
              $icons    = ['🔬','🧪','⚗️','🧫','🧬','💉'];
              $i = 0;
              foreach ($laboratorio as $lab) :
                $bg   = $icon_bgs[$i % count($icon_bgs)];
                $icon = $icons[$i % count($icons)];
                $i++;
            ?>
            <div class="ph-lab-card">

              <div class="ph-lab-icon-wrap" style="background:<?= $bg ?>; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <?php if (!empty($lab->Foto_Lab)) : ?>
                  <img src="../uploads/laboratorios/<?= htmlspecialchars($lab->Foto_Lab) ?>" 
                       alt="<?= htmlspecialchars($lab->Nome_Lab) ?>"
                       style="width: 100%; height: 100%; object-fit: cover;">
                <?php else : ?>
                  <span class="ph-lab-icon"><?= $icon ?></span>
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
                <span class="ph-badge ph-badge--active">ATIVA</span>
                <div class="ph-action-btns">
                  <a href="atualizar.php?alterar=<?= $lab->CNPJ_Lab ?>"
                     class="ph-btn ph-btn--edit"
                     title="Alterar">✏</a>
                  <a href="visualizar.php?id=<?= $lab->CNPJ_Lab ?>"
                     class="ph-btn ph-btn--view"
                     title="Visualizar">👁</a>
                  <a href="index.php?excluir=<?= $lab->CNPJ_Lab ?>"
                     class="ph-btn ph-btn--delete"
                     title="Excluir"
                     onclick="return confirm('Deseja excluir este laboratório?')">🗑</a>
                </div>
              </div>

            </div>
            <?php endforeach; ?>

          <?php else : ?>
            <div class="ph-empty-state">
              <span class="ph-empty-icon">🔬</span>
              <p>Nenhum laboratório cadastrado ainda.</p>
            </div>
          <?php endif; ?>

        </div><!-- .ph-lab-list -->

      </section>

    </div><!-- .ph-content -->

    <!-- Footer -->
    <footer class="ph-footer">
      <p>Desenvolvido por <strong>NexusDev</strong> &copy; 2026</p>
    </footer>

  </div><!-- .ph-main -->

  <!-- ═══ FAB ═══════════════════════════════════ -->
  <a href="cadastro.php" class="ph-fab" id="ph-fab" title="Novo Laboratório">+</a>

  <!-- ═══ BOTTOM NAV (mobile) ══════════════════ -->
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

    // Fecha sidebar ao clicar fora (mobile)
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
