<?php
include_once("../objetos/laboratorioController.php");

$controller = new laboratorioController();
$lab = null;

if (isset($_GET["id"])) {
    $lab = $controller->localizarLaboratorio($_GET["id"]);
}

if (!$lab) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visualizar Laboratório – PharmaPulse</title>
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
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Ficha do Laboratório</h1>
          <p class="text-secondary mb-0">Detalhes cadastrais e rastreamento.</p>
        </div>
      </div>
      <a href="index.php" class="btn btn-outline-secondary px-4 fw-bold">← Voltar</a>
    </div>

    <div class="card card-pharma" style="max-width: 800px; margin: 0 auto;">
      <!-- Header colorido -->
      <div class="p-4 text-white d-flex align-items-center gap-4" style="background: linear-gradient(135deg,#1a1c4b 0%,#2c2f8a 100%); border-radius: 12px 12px 0 0;">
        <div style="width:80px;height:80px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:2.5rem;flex-shrink:0;overflow:hidden;">
          <?php if (!empty($lab['Foto_Lab'])): ?>
            <img src="../uploads/laboratorios/<?= htmlspecialchars($lab['Foto_Lab']) ?>" alt="Logo" style="width:100%;height:100%;object-fit:cover;">
          <?php else: ?>🔬<?php endif; ?>
        </div>
        <div>
          <span class="badge bg-success mb-1">● Unidade Ativa</span>
          <h2 class="fw-bold mb-0 text-white" style="font-size:1.5rem;"><?= htmlspecialchars($lab['Nome_Lab']) ?></h2>
          <p class="mb-0 opacity-75 small">Registrado no sistema PharmaPulse</p>
        </div>
      </div>
      <!-- Corpo -->
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-md-6">
            <p class="text-secondary fw-bold small text-uppercase mb-1">Razão Social</p>
            <p class="fw-bold mb-0"><?= htmlspecialchars($lab['Nome_Lab']) ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-secondary fw-bold small text-uppercase mb-1">CNPJ</p>
            <p class="fw-bold mb-0"><?= htmlspecialchars($lab['CNPJ_Lab']) ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-secondary fw-bold small text-uppercase mb-1">E-mail Comercial</p>
            <p class="fw-bold mb-0"><?= htmlspecialchars($lab['Email_Lab']) ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-secondary fw-bold small text-uppercase mb-1">Telefone</p>
            <p class="fw-bold mb-0"><?= htmlspecialchars($lab['Telefone_Lab']) ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-secondary fw-bold small text-uppercase mb-1">CEP</p>
            <p class="fw-bold mb-0"><?= htmlspecialchars($lab['Cep_Lab']) ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-secondary fw-bold small text-uppercase mb-1">Número</p>
            <p class="fw-bold mb-0"><?= htmlspecialchars($lab['Num_Lab']) ?></p>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <div class="card-footer bg-light d-flex justify-content-end gap-2 p-3">
        <a href="atualizar.php?alterar=<?= $lab['CNPJ_Lab'] ?>" class="btn btn-pharma-primary px-4 fw-bold">✏ Editar Laboratório</a>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
