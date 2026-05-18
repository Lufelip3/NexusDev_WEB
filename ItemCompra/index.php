<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION["login"])) {
    header("Location: " . (file_exists("login.php") ? "" : "../") . "login.php");
    exit();
}

include_once "../Objetos/compraController.php";
include_once "../Objetos/itemCompraController.php";

if (!isset($_GET['notaFiscal_Entrada'])) {
    header("Location: ../Compra/index.php");
    exit();
}

$nota_fiscal = (int)$_GET['notaFiscal_Entrada'];

$compraController = new CompraController();
$compra = $compraController->localizarCompra($nota_fiscal);

if (!$compra) {
    header("Location: ../Compra/index.php");
    exit();
}

$itemController = new ItemCompraController();
$itens = $itemController->lerPorNotaFiscal($nota_fiscal);
$totalCompra = $itemController->calcularTotal($nota_fiscal);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itens da Compra – NF #<?= $nota_fiscal ?> | PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <aside class="b5-sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-opacity-25 border-light mb-3">
      <h5 class="offcanvas-title fw-bold text-white text-uppercase" id="menuLateralLabel"><img src="../cfa_logo.png" alt="Distribuidora CFA" class="img-fluid rounded" style="max-height: 70px;"></h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column flex-grow-1 p-0">
      <a href="../index.php" class="d-none d-lg-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25" style="border-color:#fff;">
        <img src="../cfa_logo.png" alt="Distribuidora CFA" class="img-fluid w-100 rounded" style="object-fit: cover;">
      </a>

      <?php include_once __DIR__ . '/../includes/sidebar_user.php'; ?>
      <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item">
          <a href="../index.php" class="nav-link">
            <span class="fs-5">🏠</span> Menu Principal
          </a>
        </li>
        <li class="nav-item"><a href="../Medicamento/index.php" class="nav-link"><span class="fs-5">💊</span> Medicamentos</a></li>
        <?php if (($_SESSION['login']->Funcao ?? '') === 'Administrador'): ?><li class="nav-item"><a href="../funcionario/index.php" class="nav-link"><span class="fs-5">👥</span> Funcionários</a></li><?php endif; ?>
        <li class="nav-item"><a href="../laboratorio/index.php" class="nav-link"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="../drogaria/index.php" class="nav-link"><span class="fs-5">🏪</span> Drogarias</a></li>
        <li class="nav-item"><a href="../Compra/index.php" class="nav-link active"><span class="fs-5">🛒</span> Compras</a></li>
        <li class="nav-item"><a href="../Venda/index.php" class="nav-link"><span class="fs-5">📈</span> Vendas</a></li>
      </ul>
      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="../logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none"><span class="fs-5">⏻</span> Sair do Sistema</a>
      </div>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">
            Itens da Compra
            <span class="badge ms-2 fw-bold <?= $compra->Finalizada ? 'bg-success' : 'bg-warning text-dark' ?>" style="border-radius:50px;font-size:.6em;">
              <?= $compra->Finalizada ? '✔ Finalizada' : 'Em Aberto' ?>
            </span>
          </h1>
          <p class="text-secondary mb-0">
            NF #<?= $nota_fiscal ?>
            &nbsp;|&nbsp; Fornecedor: <strong><?= htmlspecialchars($compra->CNPJ_Lab ?? '—') ?></strong>
            &nbsp;|&nbsp; Data: <strong><?= $compra->Data_Compra ? date('d/m/Y', strtotime($compra->Data_Compra)) : '—' ?></strong>
          </p>
        </div>
      </div>
      <a href="../Compra/index.php" class="btn btn-outline-secondary fw-bold px-4">← Voltar às Compras</a>
    </div>

    <!-- Cards de resumo -->
    <div class="row g-3 mb-4">
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #1a1c4b !important;">
          <div class="card-body">
            <p class="text-secondary mb-1 small fw-bold text-uppercase">Total de Itens</p>
            <h2 class="fw-bold mb-0" style="color:#1a1c4b;"><?= count($itens) ?></h2>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #27ae60 !important;">
          <div class="card-body">
            <p class="text-secondary mb-1 small fw-bold text-uppercase">Valor Total da NF</p>
            <h2 class="fw-bold mb-0 text-success">R$ <?= number_format($totalCompra, 2, ',', '.') ?></h2>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #f39c12 !important;">
          <div class="card-body">
            <p class="text-secondary mb-1 small fw-bold text-uppercase">Qtd. Total de Unidades</p>
            <h2 class="fw-bold mb-0" style="color:#f39c12;"><?= array_sum(array_column((array)$itens, 'Qtd_Item')) ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de itens -->
    <div class="card card-pharma">
      <div class="card-body p-0">
        <?php if (!empty($itens)): ?>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead>
              <tr>
                <th class="ps-4">Cód. Item</th>
                <th>EAN</th>
                <th>Medicamento</th>
                <th>Val. Vencimento</th>
                <th class="text-center">Qtd.</th>
                <th class="text-end">Valor Unit.</th>
                <th class="text-end pe-4">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($itens as $item): ?>
              <tr>
                <td class="ps-4 fw-bold text-secondary">#<?= $item->Cod_Item ?></td>
                <td><span class="badge bg-light text-dark border" style="font-size:.8rem;"><?= htmlspecialchars($item->EAN_Med ?? '—') ?></span></td>
                <td class="fw-bold"><?= htmlspecialchars($item->Nome_CatMed ?? $item->Nome_Med ?? '—') ?></td>
                <td>
                  <?php
                    $dataVal = $item->DataVal_Item ?? $item->dataValItemCat ?? null;
                    echo $dataVal ? date('d/m/Y', strtotime($dataVal)) : '—';
                  ?>
                </td>
                <td class="text-center">
                  <span class="badge bg-primary px-3 py-2 fs-6"><?= $item->Qtd_Item ?></span>
                </td>
                <td class="text-end">R$ <?= number_format($item->Valor_Item, 2, ',', '.') ?></td>
                <td class="text-end pe-4 fw-bold text-success">R$ <?= number_format($item->Qtd_Item * $item->Valor_Item, 2, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr class="table-light">
                <td colspan="4" class="ps-4 fw-bold text-secondary">Total Geral</td>
                <td class="text-center fw-bold"><?= array_sum(array_column((array)$itens, 'Qtd_Item')) ?> un.</td>
                <td></td>
                <td class="text-end pe-4 fw-bold text-success fs-5">R$ <?= number_format($totalCompra, 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else: ?>
        <div class="p-5 text-center">
          <p class="text-secondary fs-5 mb-0">⚠️ Nenhum item encontrado para esta nota fiscal.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>