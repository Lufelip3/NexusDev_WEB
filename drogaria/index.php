<?php
ob_start();
include_once("../Objetos/drogariaController.php");

$controller = new DrogariaController();
$drogaria   = $controller->index();
$resultados = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pesquisar"])) {
    $res = $controller->pesquisarDrogaria($_POST["pesquisar"]);
    $resultados = $res ? (is_array($res) ? $res : [$res]) : [];
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["excluir"])) {
    $controller->excluirDrogaria($_GET["excluir"]);
}

$totalDrogs = $drogaria ? count($drogaria) : 0;

// Função drogInitials removida conforme solicitação
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Gerenciamento de drogarias – PharmaPulse ERP">
  <title>Drogarias – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    /* Drogaria: borda esquerda verde para diferenciar do azul do lab */
    .ph-lab-card.drog-card { border-left-color: var(--ph-green); }
    .ph-lab-icon-wrap.drog-icon { background: var(--ph-green); }
  </style>
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

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
        <li class="nav-item"><a href="../laboratorio/index.php" class="nav-link"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="index.php" class="nav-link active" aria-current="page"><span class="fs-5">🏪</span> Drogarias</a></li>
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

  <main class="b5-main p-4 p-md-5">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral">
          <span class="fs-4">☰</span>
        </button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Drogarias</h1>
          <p class="text-secondary mb-0">Controle e rastreio de unidades farmacêuticas.</p>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="excluidos.php" class="btn btn-outline-secondary fw-bold shadow-sm">🗑 Ver Excluídos</a>
        <a href="cadastro.php" class="btn btn-pharma-success fw-bold shadow-sm px-4">+ Nova Drogaria</a>
      </div>
    </div>

    <!-- Bloco de filtro/busca -->
    <div class="card card-pharma mb-4">
      <div class="card-body p-4">
        <form method="POST" action="index.php" class="row g-3 align-items-end">
          <div class="col-md-9">
            <label for="pesquisar" class="form-label fw-bold">Pesquisar por CNPJ</label>
            <input type="text" id="pesquisar" name="pesquisar" class="form-control" placeholder="Digite o CNPJ para buscar..." value="<?= htmlspecialchars($_POST['pesquisar'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-pharma-primary w-100 fw-bold">Filtrar</button>
          </div>
        </form>

        <?php if (isset($resultados)): ?>
          <?php if (count($resultados) > 0): ?>
          <hr class="my-4">
          <h5 class="fw-bold mb-3 text-success">Resultado encontrado:</h5>
          <div class="ph-lab-list">
            <?php foreach ($resultados as $res):
              // Compatibilidade: resultado pode ser array ou objeto
              $nome  = is_array($res) ? $res['Nome_Drog']     : $res->Nome_Drog;
              $cnpj  = is_array($res) ? $res['CNPJ_Drog']     : $res->CNPJ_Drog;
              $email = is_array($res) ? $res['Email_Drog']    : $res->Email_Drog;
              $tel   = is_array($res) ? $res['Telefone_Drog'] : $res->Telefone_Drog;
              $foto  = is_array($res) ? ($res['Foto_Drog'] ?? '') : ($res->Foto_Drog ?? '');
            ?>
            <div class="ph-lab-card drog-card">
              <div class="ph-lab-icon-wrap drog-icon" <?= empty($foto) ? 'style="background:transparent;"' : '' ?>>
                <?php if (!empty($foto)): ?>
                  <img src="../uploads/drogarias/<?= htmlspecialchars($foto) ?>" alt="Logo" style="width: 46px; height: 46px; border-radius: var(--ph-radius-sm); object-fit: cover;">
                <?php else: ?>
                  <span style="font-size:2rem;">🏪</span>
                <?php endif; ?>
              </div>
              <div class="ph-lab-body">
                <strong><?= htmlspecialchars($nome ?? '') ?></strong>
                <span>📋 <?= htmlspecialchars($cnpj ?? '') ?></span>
                <span>✉ <?= htmlspecialchars($email ?? '') ?></span>
                <span>📞 <?= htmlspecialchars($tel ?? '') ?></span>
              </div>
              <div class="ph-lab-actions">
                <span class="ph-badge--active">● Ativo</span>
                <div class="ph-card-btns">
                  <a href="atualizar.php?alterar=<?= $cnpj ?>" class="ph-btn--edit">✏ Editar</a>
                  <a href="visualizar.php?id=<?= $cnpj ?>" class="ph-btn--view">👁 Ver</a>
                  <a href="index.php?excluir=<?= $cnpj ?>" class="ph-btn--delete" onclick="return confirm('Excluir esta drogaria?')">🗑</a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php else: ?>
          <hr class="my-4">
          <p class="text-secondary mb-0">Nenhum resultado encontrado para o CNPJ informado.</p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Indicador -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 bg-white shadow-sm" style="border-left: 4px solid #102c26 !important;">
          <div class="card-body">
            <h6 class="text-secondary mb-1">Drogarias Ativas</h6>
            <h2 class="fw-bold mb-0" style="color:#102c26;"><?= $totalDrogs ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista em Cartões -->
    <h3 class="fw-bold mb-3" style="color:#1a1c4b;">Unidades Registradas</h3>

    <?php if ($drogaria): ?>
    <div class="ph-lab-list">
      <?php foreach ($drogaria as $drog): ?>
      <div class="ph-lab-card drog-card">
        <div class="ph-lab-icon-wrap drog-icon" <?= empty($drog->Foto_Drog) ? 'style="background:transparent;"' : '' ?>>
          <?php if (!empty($drog->Foto_Drog)): ?>
            <img src="../uploads/drogarias/<?= htmlspecialchars($drog->Foto_Drog) ?>" alt="Logo" style="width: 46px; height: 46px; border-radius: var(--ph-radius-sm); object-fit: cover;">
          <?php else: ?>
            <span style="font-size:2rem;">🏪</span>
          <?php endif; ?>
        </div>
        <div class="ph-lab-body">
          <strong><?= htmlspecialchars($drog->Nome_Drog) ?></strong>
          <span>📋 <?= htmlspecialchars($drog->CNPJ_Drog) ?></span>
          <span>✉ <?= htmlspecialchars($drog->Email_Drog) ?></span>
          <span>📞 <?= htmlspecialchars($drog->Telefone_Drog) ?></span>
        </div>
        <div class="ph-lab-actions">
          <span class="ph-badge--active">● Ativo</span>
          <div class="ph-card-btns">
            <a href="atualizar.php?alterar=<?= $drog->CNPJ_Drog ?>" class="ph-btn--edit">✏ Editar</a>
            <a href="visualizar.php?id=<?= $drog->CNPJ_Drog ?>" class="ph-btn--view">👁 Ver</a>
            <a href="index.php?excluir=<?= $drog->CNPJ_Drog ?>" class="ph-btn--delete" onclick="return confirm('Deseja excluir esta drogaria?')">🗑</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card card-pharma p-5 text-center">
      <p class="text-secondary fs-5 mb-0">Nenhuma drogaria cadastrada.</p>
    </div>
    <?php endif; ?>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>