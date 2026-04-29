<?php
ob_start();
include_once("../Objetos/laboratorioController.php");

$controller  = new LaboratorioController();
$laboratorio = $controller->index();
$resultados  = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pesquisar"]) && isset($_POST["tipo_busca"])) {
        $resultados = $controller->pesquisarLaboratorio($_POST["tipo_busca"], $_POST["pesquisar"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["excluir"])) {
    $controller->excluirLaboratorio($_GET["excluir"]);
}

$totalLabs = $laboratorio ? count($laboratorio) : 0;

// Função labInitials removida conforme solicitação
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Gerenciamento de laboratórios – PharmaPulse ERP">
  <title>Laboratórios – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <!-- Sidebar -->
  <aside class="b5-sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-opacity-25 border-light mb-3">
      <h5 class="offcanvas-title fw-bold text-white text-uppercase" id="menuLateralLabel">Distribuidora CFA</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column flex-grow-1 p-0">
      <a href="../index.php" class="d-none d-lg-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25" style="border-color:#fff;">
        <span class="fs-4 fw-bold text-uppercase ms-3">Distribuidora CFA</span>
      </a>
      <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item"><a href="../Medicamento/index.php" class="nav-link"><span class="fs-5">💊</span> Medicamentos</a></li>
        <li class="nav-item"><a href="../funcionario/index.php" class="nav-link"><span class="fs-5">👥</span> Funcionários</a></li>
        <li class="nav-item"><a href="index.php" class="nav-link active" aria-current="page"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="../drogaria/index.php" class="nav-link"><span class="fs-5">🏪</span> Drogarias</a></li>
        <li class="nav-item"><a href="../Compra/index.php" class="nav-link"><span class="fs-5">🛒</span> Compras</a></li>
        <li class="nav-item"><a href="../Venda/index.php" class="nav-link"><span class="fs-5">📈</span> Vendas</a></li>
      </ul>
      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="../logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none">
          <span class="fs-5">⏻</span> Sair do Sistema
        </a>
      </div>
    </div>
  </aside>

  <!-- Conteúdo Principal -->
  <main class="b5-main p-4 p-md-5">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral">
          <span class="fs-4">☰</span>
        </button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Laboratórios</h1>
          <p class="text-secondary mb-0">Controle e rastreio de unidades clínicas.</p>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="excluidos.php" class="btn btn-outline-secondary fw-bold shadow-sm">🗑 Ver Excluídos</a>
        <a href="cadastro.php" class="btn btn-pharma-success fw-bold shadow-sm px-4">+ Novo Lab</a>
      </div>
    </div>

    <!-- Bloco de filtro/busca -->
    <div class="card card-pharma mb-4">
      <div class="card-body p-4">
        <form method="POST" action="index.php" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label for="tipo_busca" class="form-label fw-bold">Buscar por</label>
            <select name="tipo_busca" id="tipo_busca" class="form-select">
              <option value="nome" <?= (isset($_POST['tipo_busca']) && $_POST['tipo_busca'] == 'nome') ? 'selected' : '' ?>>Nome</option>
              <option value="cnpj" <?= (isset($_POST['tipo_busca']) && $_POST['tipo_busca'] == 'cnpj') ? 'selected' : '' ?>>CNPJ</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="pesquisar" class="form-label fw-bold">Termo</label>
            <input type="text" id="pesquisar" name="pesquisar" class="form-control" placeholder="Digite o termo..." value="<?= htmlspecialchars($_POST['pesquisar'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-pharma-primary w-100 fw-bold">Filtrar</button>
          </div>
        </form>

        <?php if ($resultados && count($resultados) > 0): ?>
        <hr class="my-4">
        <h5 class="fw-bold mb-3 text-success">Resultados encontrados (<?= count($resultados) ?>):</h5>
        <div class="ph-lab-list">
          <?php foreach ($resultados as $res): ?>
          <div class="ph-lab-card">
            <div class="ph-lab-icon-wrap" <?= empty($res->Foto_Lab) ? 'style="background:transparent;"' : '' ?>>
              <?php if (!empty($res->Foto_Lab)): ?>
                <img src="../uploads/laboratorios/<?= htmlspecialchars($res->Foto_Lab) ?>" alt="Logo" style="width: 46px; height: 46px; border-radius: var(--ph-radius-sm); object-fit: cover;">
              <?php else: ?>
                <span style="font-size:2rem;">🔬</span>
              <?php endif; ?>
            </div>
            <div class="ph-lab-body">
              <strong><?= htmlspecialchars($res->Nome_Lab ?? '') ?></strong>
              <span>📋 <?= htmlspecialchars($res->CNPJ_Lab ?? '') ?></span>
              <span>✉ <?= htmlspecialchars($res->Email_Lab ?? '') ?></span>
              <span>📞 <?= htmlspecialchars($res->Telefone_Lab ?? '') ?></span>
            </div>
            <div class="ph-lab-actions">
              <span class="ph-badge--active">● Ativo</span>
              <div class="ph-card-btns">
                <a href="atualizar.php?alterar=<?= $res->CNPJ_Lab ?>" class="ph-btn--edit">✏ Editar</a>
                <a href="visualizar.php?id=<?= $res->CNPJ_Lab ?>" class="ph-btn--view">👁 Ver</a>
                <a href="index.php?excluir=<?= $res->CNPJ_Lab ?>" class="ph-btn--delete" onclick="return confirm('Excluir este laboratório?')">🗑</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php elseif (isset($_POST['pesquisar'])): ?>
        <hr class="my-4">
        <p class="text-secondary mb-0">Nenhum resultado encontrado.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Indicador -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 bg-white shadow-sm" style="border-left: 4px solid #1a1c4b !important;">
          <div class="card-body">
            <h6 class="text-secondary mb-1">Laboratórios Ativos</h6>
            <h2 class="fw-bold mb-0" style="color:#1a1c4b;"><?= $totalLabs ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista em Cartões -->
    <h3 class="fw-bold mb-3" style="color:#1a1c4b;">Unidades Registradas</h3>

    <?php if ($laboratorio): ?>
    <div class="ph-lab-list">
      <?php foreach ($laboratorio as $lab): ?>
      <div class="ph-lab-card">
        <!-- Foto / Avatar -->
        <div class="ph-lab-icon-wrap" <?= empty($lab->Foto_Lab) ? 'style="background:transparent;"' : '' ?>>
          <?php if (!empty($lab->Foto_Lab)): ?>
            <img src="../uploads/laboratorios/<?= htmlspecialchars($lab->Foto_Lab) ?>" alt="Logo" style="width: 46px; height: 46px; border-radius: var(--ph-radius-sm); object-fit: cover;">
          <?php else: ?>
            <span style="font-size:2rem;">🔬</span>
          <?php endif; ?>
        </div>

        <!-- Dados -->
        <div class="ph-lab-body">
          <strong><?= htmlspecialchars($lab->Nome_Lab) ?></strong>
          <span>📋 <?= htmlspecialchars($lab->CNPJ_Lab) ?></span>
          <span>✉ <?= htmlspecialchars($lab->Email_Lab) ?></span>
          <span>📞 <?= htmlspecialchars($lab->Telefone_Lab) ?></span>
        </div>

        <!-- Ações -->
        <div class="ph-lab-actions">
          <span class="ph-badge--active">● Ativo</span>
          <div class="ph-card-btns">
            <a href="atualizar.php?alterar=<?= $lab->CNPJ_Lab ?>" class="ph-btn--edit">✏ Editar</a>
            <a href="visualizar.php?id=<?= $lab->CNPJ_Lab ?>" class="ph-btn--view">👁 Ver</a>
            <a href="index.php?excluir=<?= $lab->CNPJ_Lab ?>" class="ph-btn--delete" onclick="return confirm('Deseja excluir este laboratório?')">🗑</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card card-pharma p-5 text-center">
      <p class="text-secondary fs-5 mb-0">Nenhum laboratório cadastrado.</p>
    </div>
    <?php endif; ?>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
