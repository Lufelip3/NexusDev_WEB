<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once "../Objetos/itemVendaController.php";

$controller = new ItemVendaController();
$itens = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["notaFiscal_Saida"])) {
    $nota = $_GET["notaFiscal_Saida"];
    $itens = $controller->localizarItemVenda($_GET["notaFiscal_Saida"]);
} else {
    header("Location: ../Venda/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itens da NF <?= htmlspecialchars($nota) ?> – PharmaPulse (Bootstrap 5)</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <aside class="b5-sidebar d-flex flex-column flex-shrink-0 p-3">
    <a href="../index.php" class="d-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25" style="border-color: #fff;">
      <span class="fs-4 fw-bold text-uppercase ms-3">Distribuidora CFA</span>
    </a>
    
    <ul class="nav nav-pills flex-column mb-auto gap-2">
      <li class="nav-item">
        <a href="../Medicamento/index.php" class="nav-link">
          <span class="fs-5">💊</span> Medicamentos
        </a>
      </li>
      <li class="nav-item">
        <a href="../index.php" class="nav-link">
          <span class="fs-5">👥</span> Funcionários
        </a>
      </li>
      <li class="nav-item">
        <a href="../Laboratorio/index.php" class="nav-link">
          <span class="fs-5">🔬</span> Laboratórios
        </a>
      </li>
      <li class="nav-item">
        <a href="../Compra/index.php" class="nav-link">
          <span class="fs-5">🛒</span> Compras
        </a>
      </li>
      <li class="nav-item">
        <a href="../Venda/index.php" class="nav-link active" aria-current="page">
          <span class="fs-5">📈</span> Vendas
        </a>
      </li>
    </ul>

    <hr>
    <div>
      <a href="../index.php" class="btn btn-outline-light w-100 fw-bold">Sair do Sistema</a>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
      <div>
        <h1 class="display-6 fw-bold text-dark" style="color: #1a1c4b !important;">NF #<?= htmlspecialchars($nota) ?></h1>
        <p class="text-secondary mb-0">Detalhamento dos produtos vendidos.</p>
      </div>
      <div>
        <a href="../Venda/index.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm">
          ↩ Voltar às Vendas
        </a>
      </div>
    </div>

    <!-- Tabela de Itens -->
    <div class="card card-pharma">
      <div class="card-body p-0">
        <?php if($itens): ?>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead>
              <tr>
                <th scope="col" class="ps-4">Código Item</th>
                <th scope="col">Cód. Medicamento</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Validade Lote</th>
                <th scope="col">Valor Unitário</th>
                <th scope="col" class="pe-4 text-end">Total do Item</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $total = 0;
              foreach($itens as $item) : 
                 $subtotal = $item->Qtd_ItemVenda * $item->Valor_ItemVenda;
                 $total += $subtotal;
              ?>
              <tr>
                <td class="ps-4 fw-bold text-secondary">#<?= htmlspecialchars($item->Cod_ItemVenda) ?></td>
                <td><?= htmlspecialchars($item->Cod_Med) ?></td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item->Qtd_ItemVenda) ?> un.</span></td>
                <td><?= date("d/m/Y", strtotime($item->DataVal_ItemVenda)) ?></td>
                <td>R$ <?= number_format($item->Valor_ItemVenda, 2, ',', '.') ?></td>
                <td class="pe-4 text-end fw-bold text-success">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="4" class="text-end fw-bold fs-5 text-secondary">SOMA TOTAL DA NOTA:</td>
                <td colspan="2" class="text-end fw-bold fs-4 text-success pe-4">R$ <?= number_format($total, 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else: ?>
        <div class="p-5 text-center">
          <p class="text-secondary fs-5 mb-0">Esta nota fiscal ainda não possui itens registrados.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>