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
  <title>Laboratórios Excluídos – PharmaPulse</title>
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
        <li class="nav-item"><a href="index.php" class="nav-link active"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="../drogaria/index.php" class="nav-link"><span class="fs-5">🏪</span> Drogarias</a></li>
        <li class="nav-item"><a href="../Compra/index.php" class="nav-link"><span class="fs-5">🛒</span> Compras</a></li>
        <li class="nav-item"><a href="../Venda/index.php" class="nav-link"><span class="fs-5">📈</span> Vendas</a></li>
      </ul>
      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="../logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none"><span class="fs-5">⏻</span> Sair do Sistema</a>
      </div>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Laboratórios Desativados</h1>
          <p class="text-secondary mb-0">Unidades removidas – possível reativação.</p>
        </div>
      </div>
      <a href="index.php" class="btn btn-outline-secondary fw-bold px-4">← Voltar ao Painel</a>
    </div>

    <!-- Indicador -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 bg-white shadow-sm" style="border-left: 4px solid #dc3545 !important;">
          <div class="card-body">
            <h6 class="text-secondary mb-1">Desativados</h6>
            <h2 class="fw-bold mb-0 text-danger"><?= $excluidos ? count($excluidos) : 0 ?></h2>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-pharma">
      <div class="card-body p-0">
        <?php if ($excluidos): ?>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead>
              <tr>
                <th class="ps-4">Nome</th><th>CNPJ</th><th>E-mail</th><th>Telefone</th><th class="text-end pe-4">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($excluidos as $lab): ?>
              <tr>
                <td class="ps-4 fw-bold text-secondary"><?= htmlspecialchars($lab->Nome_Lab) ?></td>
                <td><?= htmlspecialchars($lab->CNPJ_Lab) ?></td>
                <td><?= htmlspecialchars($lab->Email_Lab) ?></td>
                <td><?= htmlspecialchars($lab->Telefone_Lab) ?></td>
                <td class="text-end pe-4">
                  <a href="excluidos.php?reativar=<?= urlencode($lab->CNPJ_Lab) ?>"
                     class="btn btn-sm btn-pharma-success fw-bold"
                     onclick="return confirm('Deseja reativar o laboratório <?= htmlspecialchars(addslashes($lab->Nome_Lab)) ?>?')">♻ Reativar</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="p-5 text-center">
          <p class="text-secondary fs-5 mb-0">✅ Nenhum laboratório desativado. Tudo limpo!</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>