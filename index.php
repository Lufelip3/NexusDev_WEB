<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION["login"])) {
    header("Location: " . (file_exists("login.php") ? "" : "../") . "login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard – Distribuidora CFA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <aside class="b5-sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-opacity-25 border-light mb-3">
      <h5 class="offcanvas-title fw-bold text-white text-uppercase" id="menuLateralLabel"><img src="cfa_logo.png" alt="Distribuidora CFA" class="img-fluid rounded" style="max-height: 70px;"></h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column flex-grow-1 p-0">
      <a href="index.php" class="d-none d-lg-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25" style="border-color:#fff;">
        <img src="cfa_logo.png" alt="Distribuidora CFA" class="img-fluid w-100 rounded" style="object-fit: cover;">
      </a>

      <?php include_once __DIR__ . '/includes/sidebar_user.php'; ?>

      <ul class="nav nav-pills flex-column mb-auto gap-2">
      <li class="nav-item">
        <a href="index.php" class="nav-link">
          <span class="fs-5">🏠</span> Menu Principal
        </a>
      </li>
        <li class="nav-item"><a href="Medicamento/index.php" class="nav-link"><span class="fs-5">💊</span> Medicamentos</a></li>
        <?php if (($_SESSION['login']->Funcao ?? '') === 'Administrador'): ?>
        <li class="nav-item"><a href="funcionario/index.php" class="nav-link"><span class="fs-5">👥</span> Funcionários</a></li>
        <?php endif; ?>
        <li class="nav-item"><a href="laboratorio/index.php" class="nav-link"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="drogaria/index.php" class="nav-link"><span class="fs-5">🏪</span> Drogarias</a></li>
        <li class="nav-item"><a href="Compra/index.php" class="nav-link"><span class="fs-5">🛒</span> Compras</a></li>
        <li class="nav-item"><a href="Venda/index.php" class="nav-link"><span class="fs-5">📈</span> Vendas</a></li>
      </ul>
      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none"><span class="fs-5">⏻</span> Sair do Sistema</a>
      </div>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Bem-vindo ao Sistema</h1>
          <p class="text-secondary mb-0">Selecione um módulo no menu lateral para começar.</p>
        </div>
      </div>
    </div>

    <h3 class="fw-bold mb-3" style="color:#1a1c4b;">Módulos do Sistema</h3>
    <div class="row g-4">

      <div class="col-6">
        <a href="Medicamento/index.php" class="text-decoration-none">
          <div class="card card-pharma h-100">
            <div class="card-body p-5 d-flex align-items-center gap-4">
              <span style="font-size:3rem;">💊</span>
              <div>
                <h4 class="fw-bold mb-1" style="color:#1a1c4b;">Medicamentos</h4>
                <p class="text-secondary mb-0">Controle de estoque</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <?php if (($_SESSION['login']->Funcao ?? '') === 'Administrador'): ?>
      <div class="col-6">
        <a href="funcionario/index.php" class="text-decoration-none">
          <div class="card card-pharma h-100">
            <div class="card-body p-5 d-flex align-items-center gap-4">
              <span style="font-size:3rem;">👥</span>
              <div>
                <h4 class="fw-bold mb-1" style="color:#1a1c4b;">Funcionários</h4>
                <p class="text-secondary mb-0">Cadastro de equipe</p>
              </div>
            </div>
          </div>
        </a>
      </div>
      <?php endif; ?>

      <div class="col-6">
        <a href="laboratorio/index.php" class="text-decoration-none">
          <div class="card card-pharma h-100">
            <div class="card-body p-5 d-flex align-items-center gap-4">
              <span style="font-size:3rem;">🔬</span>
              <div>
                <h4 class="fw-bold mb-1" style="color:#1a1c4b;">Laboratórios</h4>
                <p class="text-secondary mb-0">Fornecedores e fabricantes</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-6">
        <a href="drogaria/index.php" class="text-decoration-none">
          <div class="card card-pharma h-100">
            <div class="card-body p-5 d-flex align-items-center gap-4">
              <span style="font-size:3rem;">🏪</span>
              <div>
                <h4 class="fw-bold mb-1" style="color:#1a1c4b;">Drogarias</h4>
                <p class="text-secondary mb-0">Clientes e parceiros</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-6">
        <a href="Compra/index.php" class="text-decoration-none">
          <div class="card card-pharma h-100">
            <div class="card-body p-5 d-flex align-items-center gap-4">
              <span style="font-size:3rem;">🛒</span>
              <div>
                <h4 class="fw-bold mb-1" style="color:#1a1c4b;">Compras</h4>
                <p class="text-secondary mb-0">Notas fiscais de entrada</p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-6">
        <a href="Venda/index.php" class="text-decoration-none">
          <div class="card card-pharma h-100">
            <div class="card-body p-5 d-flex align-items-center gap-4">
              <span style="font-size:3rem;">📈</span>
              <div>
                <h4 class="fw-bold mb-1" style="color:#1a1c4b;">Vendas</h4>
                <p class="text-secondary mb-0">Faturamento e saídas</p>
              </div>
            </div>
          </div>
        </a>
      </div>

    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
