<?php
include_once ("../Objetos/medicamentoController.php");

$controller = new medicamentoController();
$a = null;

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["alterar"])){
    $a = $controller->localizarMedicamento($_GET["alterar"]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["atualizar"])){
    $controller->atualizarMedicamento($_POST["medicamento"], $_FILES["medicamento"] ?? null);
    header("Location: index.php");
    exit();

} else {
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Medicamento – PharmaPulse</title>
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
        <li class="nav-item"><a href="index.php" class="nav-link active"><span class="fs-5">💊</span> Medicamentos</a></li>
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
    <div class="d-flex align-items-center gap-3 mb-4">
      <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
      <div>
        <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Editar Medicamento</h1>
        <p class="text-secondary mb-0">Atualize os dados deste item no estoque.</p>
      </div>
    </div>

    <div class="card card-pharma" style="max-width: 700px; margin: 0 auto;">
      <div class="card-body p-4">
        <?php if ($a): ?>
        <form action="atualizar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="medicamento[Cod_Med]" value="<?= htmlspecialchars($a->Cod_Med) ?>">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-bold">Nome</label>
              <input type="text" name="medicamento[Nome_Med]" class="form-control" value="<?= htmlspecialchars($a->Nome_Med) ?>">
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">Descrição</label>
              <input type="text" name="medicamento[Desc_Med]" class="form-control" value="<?= htmlspecialchars($a->Desc_Med) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Data Validade</label>
              <input type="date" name="medicamento[DataVal_Med]" class="form-control" value="<?= htmlspecialchars($a->DataVal_Med) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Quantidade</label>
              <input type="number" name="medicamento[Qtd_Med]" class="form-control" value="<?= htmlspecialchars($a->Qtd_Med) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Valor (R$)</label>
              <input type="number" step="0.01" name="medicamento[Valor_Med]" class="form-control" value="<?= htmlspecialchars($a->Valor_Med) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Foto do Medicamento</label>
              <?php if(isset($a->Foto_Med) && $a->Foto_Med): ?>
                <div class="mb-2">
                  <img src="../uploads/medicamentos/<?= $a->Foto_Med ?>" height="50" style="border-radius:8px;">
                  <span class="text-secondary ms-2 small">Foto atual</span>
                </div>
              <?php endif; ?>
              <input type="file" name="medicamento[Foto_Med]" class="form-control" accept="image/*">
            </div>
          </div>
          <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
            <a href="index.php" class="btn btn-outline-secondary px-4 fw-bold">Cancelar</a>
            <button type="submit" name="atualizar" class="btn btn-pharma-success px-4 fw-bold">Salvar Alterações</button>
          </div>
        </form>
        <?php else: ?>
          <div class="p-4 text-center"><p class="text-secondary">Medicamento não encontrado. <a href="index.php">Voltar</a></p></div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>