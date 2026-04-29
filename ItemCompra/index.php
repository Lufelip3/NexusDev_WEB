<?php
include_once "../Objetos/itemCompraController.php";

if (!isset($_GET['notaFiscal_Entrada'])) {
    header("Location: ../Compra/index.php");
    exit();
}

$notaFiscal = $_GET['notaFiscal_Entrada'];
$controller = new ItemCompraController();
$itens = $controller->lerPorNotaFiscal($notaFiscal);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itens da Compra NF <?= htmlspecialchars($notaFiscal) ?> – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
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
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">NF #<?= htmlspecialchars($notaFiscal) ?></h1>
          <p class="text-secondary mb-0">Detalhamento dos itens da compra.</p>
        </div>
      </div>
      <a href="../Compra/index.php" class="btn btn-outline-secondary fw-bold px-4">↩ Voltar às Compras</a>
    </div>

    <div class="card card-pharma">
      <div class="card-body p-0">
        <?php if ($itens): ?>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead>
              <tr>
                <th class="ps-4">Cód. Item</th>
                <th>Nome Medicamento</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
                <th>Data Venda</th>
                <th class="pe-4 text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              foreach ($itens as $item):
                  $subtotal = $item->Qtd_Item * $item->Valor_Item;
                  $total += $subtotal;
              ?>
              <tr>
                <td class="ps-4 fw-bold text-secondary">#<?= $item->Cod_Item ?></td>
                <td class="fw-bold"><?= htmlspecialchars($item->Nome_Med ?? $item->Nome_CatMed ?? '—') ?></td>
                <td><span class="badge bg-light text-dark border"><?= $item->Qtd_Item ?> un.</span></td>
                <td>R$ <?= number_format($item->Valor_Item, 2, ',', '.') ?></td>
                <td><?= $item->Data_Venda ?></td>
                <td class="pe-4 text-end fw-bold text-success">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="4" class="text-end fw-bold fs-5 text-secondary">TOTAL DA COMPRA:</td>
                <td colspan="2" class="text-end fw-bold fs-4 text-success pe-4">R$ <?= number_format($total, 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else: ?>
        <div class="p-5 text-center">
          <p class="text-secondary fs-5 mb-0">Nenhum item encontrado para esta compra.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
