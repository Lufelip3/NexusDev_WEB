<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once("../Objetos/medicamentoController.php");

$controller = new medicamentoController();
$medicamentos = $controller->index();
$a = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pesquisar"])) {
        $a = $controller->pesquisaMedicamento($_POST["pesquisar"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["excluir"])) {
        $controller->excluirMedicamento($_GET["excluir"]);
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medicamentos – PharmaPulse</title>
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
        <li class="nav-item"><a href="index.php" class="nav-link active" aria-current="page"><span class="fs-5">💊</span> Medicamentos</a></li>
        <li class="nav-item"><a href="../funcionario/index.php" class="nav-link"><span class="fs-5">👥</span> Funcionários</a></li>
        <li class="nav-item"><a href="../laboratorio/index.php" class="nav-link"><span class="fs-5">🔬</span> Laboratórios</a></li>
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
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Medicamentos</h1>
          <p class="text-secondary mb-0">Controle do estoque de medicamentos.</p>
        </div>
      </div>
      <a href="cadastro.php" class="btn btn-pharma-success fw-bold shadow-sm px-4">+ Novo Medicamento</a>
    </div>

    <!-- Busca -->
    <div class="card card-pharma mb-4">
      <div class="card-body p-4">
        <form method="POST" action="index.php" class="row g-3 align-items-end">
          <div class="col-md-9">
            <label for="pesquisar" class="form-label fw-bold">Pesquisar por Nome ou Código</label>
            <input type="text" id="pesquisar" name="pesquisar" class="form-control" placeholder="Digite o nome ou código do medicamento...">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-pharma-primary w-100 fw-bold">Filtrar</button>
          </div>
        </form>

        <?php if ($a): ?>
        <hr class="my-4">
        <h5 class="fw-bold mb-3">Resultado encontrado:</h5>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead><tr>
              <th class="ps-4">Cód.</th><th>Nome</th><th>Qtd.</th><th>Validade</th><th>Valor</th><th class="text-end pe-4">Ações</th>
            </tr></thead>
            <tbody>
              <tr>
                <td class="ps-4 fw-bold text-secondary">#<?= htmlspecialchars($a->Cod_Med) ?></td>
                <td class="fw-bold"><?= htmlspecialchars($a->Nome_Med) ?></td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a->Qtd_Med) ?> un.</span></td>
                <td><?= htmlspecialchars($a->DataVal_Med) ?></td>
                <td class="fw-bold text-success">R$ <?= number_format($a->Valor_Med, 2, ',', '.') ?></td>
                <td class="text-end pe-4">
                  <a href="atualizar.php?alterar=<?= $a->Cod_Med ?>" class="btn btn-sm btn-pharma-primary me-1">✏ Editar</a>
                  <a href="index.php?excluir=<?= $a->Cod_Med ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este medicamento?')">🗑</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Indicadores -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 bg-white shadow-sm" style="border-left: 4px solid #1a1c4b !important;">
          <div class="card-body">
            <h6 class="text-secondary mb-1">Total em Estoque</h6>
            <h2 class="fw-bold mb-0" style="color:#1a1c4b;"><?= $medicamentos ? count($medicamentos) : 0 ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista -->
    <h3 class="fw-bold mb-3" style="color:#1a1c4b;">Estoque Completo</h3>
    <div class="card card-pharma">
      <div class="card-body p-0">
        <?php if ($medicamentos): ?>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead>
              <tr>
                <th class="ps-4">Cód.</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Qtd.</th>
                <th>Validade</th>
                <th>Valor</th>
                <th class="text-end pe-4">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($medicamentos as $med): ?>
              <tr>
                <td class="ps-4 fw-bold text-secondary">#<?= htmlspecialchars($med->Cod_Med) ?></td>
                <td class="fw-bold"><?= htmlspecialchars($med->Nome_Med) ?></td>
                <td class="text-secondary"><?= htmlspecialchars($med->Desc_Med) ?></td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($med->Qtd_Med) ?> un.</span></td>
                <td><?= htmlspecialchars($med->DataVal_Med) ?></td>
                <td class="fw-bold text-success">R$ <?= number_format($med->Valor_Med, 2, ',', '.') ?></td>
                <td class="text-end pe-4">
                  <a href="atualizar.php?alterar=<?= $med->Cod_Med ?>" class="btn btn-sm btn-pharma-primary me-1">✏</a>
                  <a href="index.php?excluir=<?= $med->Cod_Med ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este medicamento?')">🗑</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="p-5 text-center">
          <p class="text-secondary fs-5 mb-0">Nenhum medicamento cadastrado.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>